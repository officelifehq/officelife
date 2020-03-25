@extends('layouts.skeleton')

@section('content')

<div class="flex justify-center mt-16">
  <div class="w-full">
    <div class="px-2 sm:px-0">
      <!-- Blank state -->
      {{-- <div class="cf mt4 mt5-l mw7 center">
        <div class="fl w-100 w-25-m w-50-l pr2-l">
          <a href="/company/create">
            <div class="pa3-l">
              <div class="br3 mb3 bg-white box pa3 tc relative home-box" data-cy="create-company-blank-state">
                <h3>{{ trans('home.create_company') }}</h3>
                <p>{{ trans('home.create_company_desc') }}</p>
                <img src="/img/home/create-company.png" class="home-company absolute" />
              </div>
            </div>
          </a>
        </div>
        <div class="fl w-100 w-25-m w-50-l">
          <a href="/company/create">
            <div class="pa3-l">
              <div class="br3 mb3 bg-white box pa3 tc relative home-box">
                <h3>{{ trans('home.join_company') }}</h3>
                <p>{{ trans('home.join_company_desc') }}</p>
                <img src="/img/home/join-company.png" class="home-join absolute" />
              </div>
            </div>
          </a>
        </div>
      </div> --}}

      <!-- List of companies -->
      <div>
        <div class="mt-4 xl:mt-5 md:w-3/5 mr-auto ml-auto section-btn relative">
          <p>
            <span class="px-1 bg-white rounded">
              {{ trans('home.companies_part_of') }}
            </span>
            <a href="/company/create" class="absolute md:block hidden">
              {{ trans('home.create_company_cta') }}
            </a>
          </p>
        </div>
        <div class="mt-4 xl:mt-5 md:w-3/5 mr-auto ml-auto">
          @foreach ($employees as $employee)
          <div class="fl w-100 w-25-m w-third-l pr2">
            <a href="'/employee.company_id/dashboard'">
              <div class="br3 mb3 bg-white box pa3 home-index-company fw5 relative">
                A CHANGER
                <span class="absolute normal f6">
                  {{ trans('home.number_of_employees') }}
                </span>
              </div>
            </a>
          </div>
          @endforeach
        </div>
        <div class="w-100 dn-ns db mt2">
          <a href="/company/create" class="btn br3 pa3 white no-underline bb-0 db tc">
            {{ trans('home.create_company_cta') }}
          </a>
        </div>
      </div>
    </div>
</div>
</div>

@endsection
