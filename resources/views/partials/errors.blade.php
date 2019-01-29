@if (isset($errors))
  @if (count($errors) > 0)
    <div class="ba br2 mb3 b-red bg-orange">
      <ul class="list pl2 f6">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif
@endif
