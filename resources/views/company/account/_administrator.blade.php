As an administrator, you can...

<ul>
  <li>Upgrade to a paid account</li>
  <li><a href="{{ tenant('/account/audit') }}">View audit log</a></li>
  <li>Export data from this account</li>
  <li>Cancel this account</li>
  <li>Change company information</li>
  @if (!$company->has_dummy_data)
  <li><a href="{{ tenant('/account/dummy') }}">Generate fake data</a></li>
  @else
  <li><a href="{{ tenant('/account/dummy') }}">Remove fake data</a></li>
  @endif
</ul>
