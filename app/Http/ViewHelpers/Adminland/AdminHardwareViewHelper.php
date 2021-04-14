<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Company;
use App\Models\Company\AuditLog;
use App\Models\Company\Employee;
use App\Models\Company\Hardware;
use Illuminate\Support\Collection;

class AdminHardwareViewHelper
{
    /**
     * Collection containing all the information about the hardware used in the
     * company.
     *
     * @param mixed $hardware
     * @return array|null
     */
    public static function hardware($hardware): ?array
    {
        // if no hardware
        if ($hardware->count() == 0) {
            return null;
        }

        // building a collection of hardware
        $hardwareCollection = collect([]);
        foreach ($hardware as $item) {
            $employee = $item->employee;

            $hardwareCollection->push([
                'id' => $item->id,
                'name' => $item->name,
                'serial_number' => $item->serial_number,
                'employee' => ($employee) ? [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'avatar' => ImageHelper::getAvatar($employee, 18),
                ] : null,
            ]);
        }

        // statistics
        $numberOfHardwareNotLent = $hardware->filter(function ($item) {
            return is_null($item->employee);
        })->count();

        return [
            'hardware_collection' => $hardwareCollection,
            'number_hardware_not_lent' => $numberOfHardwareNotLent,
            'number_hardware_lent' => $hardware->count() - $numberOfHardwareNotLent,
        ];
    }

    /**
     * Collection containing all the employees.
     *
     * @param Company $company
     * @return Collection|null
     */
    public static function employeesList(Company $company): ?Collection
    {
        $employees = $company->employees()->notLocked()->get();

        $employeesCollection = collect([]);
        foreach ($employees as $employee) {
            $employeesCollection->push([
                'value' => $employee->id,
                'label' => $employee->name,
            ]);
        }

        return $employeesCollection;
    }

    /**
     * Collection containing all the information about available hardware.
     *
     * @param mixed $hardware
     * @return array|null
     */
    public static function availableHardware($hardware): ?array
    {
        // if no hardware
        if ($hardware->count() == 0) {
            return null;
        }

        $availableHardware = $hardware->filter(function ($item) {
            return is_null($item->employee);
        });

        // building a collection of hardware
        $hardwareCollection = collect([]);
        foreach ($availableHardware as $item) {
            $employee = $item->employee;

            $hardwareCollection->push([
                'id' => $item->id,
                'name' => $item->name,
                'serial_number' => $item->serial_number,
                'employee' => ($employee) ? [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'avatar' => ImageHelper::getAvatar($employee, 18),
                ] : null,
            ]);
        }

        // statistics
        $numberOfHardwareNotLent = $hardware->filter(function ($item) {
            return is_null($item->employee);
        })->count();

        return [
            'hardware_collection' => $hardwareCollection,
            'number_hardware_not_lent' => $numberOfHardwareNotLent,
            'number_hardware_lent' => $hardware->count() - $numberOfHardwareNotLent,
        ];
    }

    /**
     * Collection containing all the information about hardware already given to
     * employees.
     *
     * @param mixed $hardware
     * @return array|null
     */
    public static function lentHardware($hardware): ?array
    {
        // if no hardware
        if ($hardware->count() == 0) {
            return null;
        }

        $lentHardware = $hardware->filter(function ($item) {
            return ! is_null($item->employee);
        });

        // building a collection of hardware
        $hardwareCollection = collect([]);
        foreach ($lentHardware as $item) {
            $employee = $item->employee;

            $hardwareCollection->push([
                'id' => $item->id,
                'name' => $item->name,
                'serial_number' => $item->serial_number,
                'employee' => ($employee) ? [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'avatar' => ImageHelper::getAvatar($employee, 18),
                ] : null,
            ]);
        }

        // statistics
        $numberOfHardwareNotLent = $hardware->filter(function ($item) {
            return is_null($item->employee);
        })->count();

        return [
            'hardware_collection' => $hardwareCollection,
            'number_hardware_not_lent' => $numberOfHardwareNotLent,
            'number_hardware_lent' => $hardware->count() - $numberOfHardwareNotLent,
        ];
    }

    /**
     * Get the complete history of what happened to the item.
     * This is an expensive request, I don't really like it but I don't know
     * how to do it differently.
     *
     * @param Hardware $hardware
     * @param Employee $employee
     * @return Collection|null
     */
    public static function history(Hardware $hardware, Employee $employee): ?Collection
    {
        $logs = AuditLog::where('company_id', $hardware->company_id)
            ->where('action', 'hardware_created')
            ->orWhere('action', 'hardware_updated')
            ->orWhere('action', 'hardware_destroyed')
            ->orWhere('action', 'hardware_lent')
            ->orWhere('action', 'hardware_regained')
            ->orderBy('created_at', 'desc')
            ->get();

        // now filter by hardware id
        $logs = $logs->filter(function ($log) use ($hardware) {
            return strpos($log->objects, '"hardware_id":'.$hardware->id) !== false;
        });

        // now preparing the sentences
        $logsCollection = collect([]);
        foreach ($logs as $log) {
            $sentence = '';

            if ($log->action == 'hardware_created' || $log->action == 'hardware_updated') {
                $sentence = trans('account.hardware_log_'.$log->action, ['name' => $log->object->{'hardware_name'}]);
            }

            if ($log->action == 'hardware_lent' || $log->action == 'hardware_regained') {
                $sentence = trans('account.hardware_log_'.$log->action, ['name' => $log->object->{'employee_name'}]);
            }

            $logsCollection->push([
                'id' => $log->id,
                'date' => DateHelper::formatDate($log->audited_at, $employee->timezone),
                'sentence' => $sentence,
            ]);
        }

        return $logsCollection;
    }
}
