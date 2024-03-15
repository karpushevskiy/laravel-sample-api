@component('mail::message')

{{-- The body of your message --}}
@if(!empty($verificationCode))
<h1 class="mail-verification-title">@lang('notifications.email_confirmation.code_line')</h1>
<h6 class="mail-verification-code">
    @foreach(str_split($verificationCode) as $symbol)<span>{{ $symbol }}</span>@endforeach
</h6>
@endif

@endcomponent
