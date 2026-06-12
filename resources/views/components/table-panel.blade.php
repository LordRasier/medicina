<div class="panel ui-table-panel">
    <div class="panel-hdr">
        <div>
            <h2>{{ $title }}</h2>
            @isset($description)
                <div class="ui-panel-description">{{ $description }}</div>
            @endisset
        </div>

        @isset($toolbar)
            <div class="ui-panel-toolbar">
                {{ $toolbar }}
            </div>
        @endisset
    </div>
    <div class="panel-container show">
        <div class="panel-content {{ $contentClass ?? '' }}">
            {{ $slot }}
        </div>
    </div>
</div>
