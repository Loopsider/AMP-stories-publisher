@if (!isset($page_id) || $page_id == $page['id'])
<amp-story-page id = "{{ $page['id'] }}"
  @if (!empty($page['class'])) class="{{ $page['class']}}" @endif
  @if (!empty($page['auto-advance-after'])) auto-advance-after="{{ $page['auto-advance-after'] }}s"@endif
  @if (!empty($page['background-audio'])) background-audio="{{ $page['background-audio'] }}"@endif>
  @each('story-grid-layer', $page['layers'], 'layer')
  @if (!empty($page['cta_layer']))
    @include('story-cta-layer', ['cta_layer' => $page['cta_layer']])
  @endif
</amp-story-page>
@endif
