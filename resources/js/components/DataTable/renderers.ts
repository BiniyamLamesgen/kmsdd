import { Edit, Eye, Trash2 } from 'lucide-vue-next';
import { h } from 'vue';
import Badge from '../../components/Badge.vue';
import { Button } from '../ui/button';
import { MapPin } from 'lucide-vue-next';
import type { DataTableAction } from '../../types/datatable'
// TODO: Replace the import path below with the correct one where DataTableAction is exported

// Status Badge Renderer
export function renderStatusBadge(
    value: boolean,
    trueLabel = 'Active',
    falseLabel = 'Inactive',
    trueVariant: 'default' | 'secondary' | 'destructive' | 'outline' = 'default',
    falseVariant: 'default' | 'secondary' | 'destructive' | 'outline' = 'destructive'
) {
    return h(Badge, {
        variant: value ? trueVariant : falseVariant,
    }, {
        default: () => value ? trueLabel : falseLabel
    });
}

// Type Badge Renderer (for module type: core/add-on)
export function renderTypeBadge(
    value: boolean,
    trueLabel = 'Core',
    falseLabel = 'Add-on',
    trueVariant: 'default' | 'secondary' | 'destructive' | 'outline' = 'default',
    falseVariant: 'default' | 'secondary' | 'destructive' | 'outline' = 'secondary'
) {
    return h(Badge, {
        variant: value ? trueVariant : falseVariant,
    }, {
        default: () => value ? trueLabel : falseLabel
    });
}

export function renderImage(url: string, alt = 'Document Image', size = 40) {
    if (!url) return '-';
    return h('img', {
        src: url,
        alt,
        style: `width:${size}px;height:${size}px;object-fit:cover;border-radius:6px;`,
        loading: 'lazy'
    });
}


// Date Renderer
export function renderDate(value: string | Date, format = 'MMM DD, YYYY') {
    if (!value) return '-';

    const date = new Date(value);
    if (isNaN(date.getTime())) return '-';

    // Simple date formatting (you can enhance this with moment.js or date-fns)
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: '2-digit'
    });
}

// Currency Renderer
export function renderCurrency(value: number, currency = 'USD', locale = 'en-US') {
    if (value === null || value === undefined) return '-';

    return new Intl.NumberFormat(locale, {
        style: 'currency',
        currency: currency,
    }).format(value);
}

// Truncated Text Renderer
export function renderTruncatedText(value: string, maxLength = 50) {
    if (!value) return '-';

    if (value.length <= maxLength) return value;

    return h('span', {
        title: value, // Show full text on hover
    }, value.substring(0, maxLength) + '...');
}


// HTML Text Renderer
export function renderHtmlText(value: string) {
    if (!value) return '-';
    // Truncate HTML text to avoid overflow
    const maxLength = 50;
    const trimmed = value.trim();
    const displayText = trimmed.length > maxLength ? trimmed.substring(0, maxLength) + '...' : trimmed;
    return h('span', {
        innerHTML: displayText,
        title: trimmed, // Show full HTML on hover
        style: 'display: inline-block; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'
    });
}

// Number Renderer with formatting
export function renderNumber(value: number, decimals = 0) {
    if (value === null || value === undefined) return '-';

    return new Intl.NumberFormat('en-US', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    }).format(value);
}

// Actions Factory - Creates common action configurations
export function createActionButton(
    icon: any,
    variant: 'default' | 'secondary' | 'destructive' | 'outline' | 'ghost' | 'link' = 'outline',
    tooltip?: string
) {
    return {
        icon,
        variant,
        tooltip,
    };
}

// Common Action Configurations
export const CommonActions = {
    view: (onClick: (row: any) => void, show?: (row: any) => boolean): DataTableAction => ({
        ...createActionButton(Eye, 'outline', 'View'),
        onClick,
        show,
    }),

    edit: (onClick: (row: any) => void, show?: (row: any) => boolean): DataTableAction => ({
        ...createActionButton(Edit, 'outline', 'Edit'),
        onClick,
        show,
    }),

    delete: (onClick: (row: any) => void, show?: (row: any) => boolean): DataTableAction => ({
        ...createActionButton(Trash2, 'destructive', 'Delete'),
        onClick,
        show,
    }),
};

// Column Factory - Creates common column configurations
export function createColumn(
    key: string,
    label: string,
    options: {
        sortable?: boolean;
        searchable?: boolean;
        width?: string;
        render?: (value: any, row: any) => any;
        class?: string;
        headerClass?: string;
    } = {}
) {
    return {
        key,
        label,
        sortable: options.sortable ?? true,
        searchable: options.searchable ?? true,
        width: options.width,
        render: options.render,
        class: options.class,
        headerClass: options.headerClass,
    };
}

// Common Column Configurations
export const CommonColumns = {
    checkbox: {
        key: 'checkbox',
        label: '',
        sortable: false,
        searchable: false,
        width: '50px',
        class: 'text-center',
        headerClass: 'text-center'
    },

    id: () => createColumn('id', 'ID', {
        width: '80px',
        class: 'text-center',
        headerClass: 'text-center'
    }),

    name: () => createColumn('name', 'Name'),

    email: () => createColumn('email', 'Email'),

    status: (field = 'is_active') => createColumn(field, 'Status', {
        sortable: true,
        searchable: false,
        width: '120px',
        render: (value) => renderStatusBadge(value),
        class: 'text-center',
        headerClass: 'text-center'
    }),

    createdAt: (field = 'created_at') => createColumn(field, 'Created', {
        sortable: true,
        searchable: false,
        width: '140px',
        render: (value) => renderDate(value),
    }),

    updatedAt: (field = 'updated_at') => createColumn(field, 'Updated', {
        sortable: true,
        searchable: false,
        width: '140px',
        render: (value) => renderDate(value),
    }),

    description: (maxLength = 100) => createColumn('description', 'Description', {
        sortable: false,
        render: (value) => renderTruncatedText(value, maxLength),
    }),

    amount: (field = 'amount', currency = 'USD') => createColumn(field, 'Amount', {
        sortable: true,
        searchable: false,
        width: '120px',
        render: (value) => renderCurrency(value, currency),
        class: 'text-right',
        headerClass: 'text-right'
    }),
};

// Status Label Renderer for string statuses
export function renderStatusLabel(label: string) {
    if (!label) return '-';
    return h(Badge, { variant: 'default' }, { default: () => label });
}

// Map Location Renderer
export function renderMapLocation(locationUrl?: string) {
    if (!locationUrl) return '-';
    return h(
        'button',
        {
            onClick: () => window.open(locationUrl, '_blank'),
            title: 'View on Map',
            class: 'text-blue-600 hover:text-blue-800 p-1',
        },
        [h(MapPin, { class: 'w-5 h-5' })]
    );
}

// Action Groups Factory
export function createActionGroup(baseRoute: string, options: {
    view?: boolean | ((row: any) => boolean);
    edit?: boolean | ((row: any) => boolean);
    delete?: boolean | ((row: any) => boolean);
    onDelete?: (row: any) => void;
}): DataTableAction[] {
    const actions: DataTableAction[] = [];

    if (options.view) {
        actions.push(CommonActions.view(
            (row) => window.open(`${baseRoute}/${row.id}`, '_blank'),
            typeof options.view === 'function' ? options.view : undefined
        ));
    }

    if (options.edit) {
        actions.push(CommonActions.edit(
            (row) => window.location.href = `${baseRoute}/${row.id}/edit`,
            typeof options.edit === 'function' ? options.edit : undefined
        ));
    }

    if (options.delete) {
        actions.push(CommonActions.delete(
            options.onDelete || ((row) => {
                if (confirm('Are you sure you want to delete this item?')) {
                    // Default delete logic - implement in your component
                    throw new Error('Delete action not implemented. Please provide onDelete handler.');
                }
            }),
            typeof options.delete === 'function' ? options.delete : undefined
        ));
    }

    return actions;
}

// Certification Image Renderer
export function renderCertificationImage(image_url: string, alt = 'Certificate', size = 50) {
    if (!image_url) return '-';
    
    return h('div', {
        class: 'flex items-center justify-center',
        style: `min-width:${size}px;min-height:${size}px;`
    }, [
        h('img', {
            src: image_url,
            alt,
            class: 'transition-all duration-200 hover:scale-110',
            style: `max-width:${size}px;max-height:${size}px;border-radius:4px;border:1px solid #eee;object-fit:cover;`,
            loading: 'lazy',
            onclick: () => window.open(image_url, '_blank'),
            onerror: (e) => {
                const target = e.target as HTMLImageElement;
                console.error('Failed to load image:', image_url);
                target.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjgwIiBoZWlnaHQ9IjgwIiBmaWxsPSIjZjVmNWY1IiAvPjx0ZXh0IHg9IjQwIiB5PSI0MCIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjEyIiBmaWxsPSIjOTk5IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBhbGlnbm1lbnQtYmFzZWxpbmU9Im1pZGRsZSI+SW1hZ2UgTm90IEZvdW5kPC90ZXh0Pjwvc3ZnPg==';
                target.classList.add('error-image');
                target.style.opacity = '0.6';
            }
        })
    ]);
}
