@php
    /**
     * @param string $banner
     * @param string $title
     * @param string $count
     */
    $classes = ['mb-8'];
    if ($banner) {
        $classes[] = 'wp-block-cover';
        $classes[] = 'min-h-[160px] aspect-ratio:unset';
    }
@endphp
<div class="{{ implode(' ', $classes) }}">
    @if ($banner)
        {!! wp_get_attachment_image($banner, 'full', false, ['class' => 'absolute inset-0 w-full h-full object-cover', 'loading' => 'lazy', 'decoding' => 'async']) !!}
        <span aria-hidden="true"
            class="wp-block-cover__background has-secondary-500-background-color has-background-dim-70 has-background-dim">
        </span>
        <div class="wp-block-cover__inner-container flex items-center gap-5">
            @if ($title)
                <h1 class="m-0 text-4xl text-white">
                    <strong>{{ $title }}</strong>
                </h1>
            @endif
            @if ($count)
                <span class="text-2xl text-white">
                    {{ $count }}
                </span>
            @endif
        </div>
    @endif
</div>