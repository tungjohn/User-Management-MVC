<div>
    <div class="alert alert-success">
        <p>{! $activePage['content'] ?? '' !}</p>
        <p><a href="{{$activePage['active_action_link'] ?? ''}}" class="resend-active-email">{{ $activePage['active_action_content'] ?? '' }}</a></p>
        <form action="/auth/resend-email-active" method="post" id="resend-active-form">
            <input type="hidden" name="active_token" value="{{ $activePage['active_token'] ?? old('active_token') ?? '' }}">
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Additional JavaScript can be added here if needed
        document.querySelector('.resend-active-email').addEventListener('click', function(e) {
            e.preventDefault();
            // Logic to resend activation email can be added here
            document.getElementById('resend-active-form').submit();
        });
    });
</script>