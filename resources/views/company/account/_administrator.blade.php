As an administrator, you can...

<ul>
  <li>Upgrade to a paid account</li>
  <li><a href="{{ tenant('/account/audit') }}">View audit log</a></li>
  <li>Export data from this account</li>
  <li>Cancel this account</li>
  @if (!$company->has_dummy_data)
  <a href="{{ tenant('/account/dummy') }}">Generate fake data</a>
  @else
  <a href="{{ tenant('/account/dummy') }}">Remove fake data</a>
  @endif
</ul>
