@props(['menu'])

<li class="menu-item{{ request()->path() == $menu->url ? ' active' : '' }}">
    <a href="{{ $menu->get_child->count() != 0 ? 'javascript:void(0);' : $menu->url}}" class="menu-link {{ $menu->get_child->count() != 0 ? 'menu-toggle' : '' }}" {{ $menu->get_child->count() == 0 ? 'wire:navigate' : ''}}>
        <i class="menu-icon icon-base {{ $menu->icon }}"></i>
        <div data-i18n="{{ $menu->title }}">{{ $menu->title }}</div>
    </a>
    @if($menu->get_child->count() != 0)
        <ul class="menu-sub">
            @foreach ($menu->get_child as $child)
                <li class="menu-item">
                    <a href="{{ $menu->app->subdomain.'/'.$menu->url.'/'.$child->url }}" class="menu-link" wire:navigate>
                        <div data-i18n="{{ $child->title }}">{{ $child->title }}</div>
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</li>
