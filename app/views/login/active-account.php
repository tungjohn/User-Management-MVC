<div>
    <div class="alert alert-success">
        <p>{{ $activePage['content'] ?? '' }}</p>
        <p><a href="{{$activePage['active_action_link'] ?? ''}}">{{ $activePage['active_action_content'] ?? '' }}</a></p>
    </div>
</div>
