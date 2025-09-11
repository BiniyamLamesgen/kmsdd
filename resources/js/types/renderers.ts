// renderers.ts
import { h } from 'vue';
import { MapPin } from 'lucide-vue-next';

/**
 * Create a column configuration with optional custom rendering
 */
export function createColumn<T>(
  key: keyof T | string,
  label: string,
  options: {
    sortable?: boolean;
    searchable?: boolean;
    class?: string;
    render?: (row: T) => any;
  } = {}
) {
  return {
    key,
    label,
    sortable: options.sortable ?? false,
    searchable: options.searchable ?? false,
    class: options.class ?? '',
    render: options.render,
  };
}

/**
 * Renderer to show map location icon with a link
 */
export function renderMapLocation<T extends { map_location?: string }>() {
  return (row: T) => {
    if (!row.map_location) return '';
    return h(
      'button',
      {
        title: 'Open Map',
        onClick: () => window.open(row.map_location, '_blank'),
        class: 'text-blue-600 hover:text-blue-800 p-1',
      },
      [h(MapPin, { class: 'w-5 h-5' })]
    );
  };
}
