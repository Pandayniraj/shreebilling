
@foreach (session('flash_notification', collect())->toArray() as $message)

    @if ($message['overlay'])
        @include('flash::modal', [
            'modalClass' => 'flash-modal',
            'title'      => $message['title'],
            'body'       => $message['message']
        ])
    @else
        <div class="alert
                    alert-{{ $message['level'] }}
                    {{ $message['important'] ? 'alert-important' : '' }}"
                    role="alert"
        >
            @if ($message['important'])
                <button type="button"
                        class="close"
                        data-dismiss="alert"
                        aria-hidden="true"
                >&times;</button>
            @endif

            {!! $message['message'] !!}
        </div>
    @endif
@endforeach

{{ session()->forget('flash_notification') }}
@if (\Config::get('settings.app_flash_notification_auto_hide_enabled', true))
    <script>
        $(document).ready (function(){
            $('div.alert').not('.alert-important').delay(5500).slideUp(600, function() {
                $(this).alert('close');
            });
        });
    </script>
@endif
