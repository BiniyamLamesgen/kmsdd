import type { LucideIcon } from "lucide-vue-next";
import type { Config } from "ziggy-js";

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface Faq {
    id: number;
    question: string;
    answer: string;
}

export interface DataTableAction<T = any> {
    label?: string;
    icon?: any;
    variant?:
        | "default"
        | "secondary"
        | "destructive"
        | "outline"
        | "ghost"
        | "link";
    onClick: (row: T) => void;
    show?: (row: T) => boolean;
    class?: string;
    tooltip?: string;
}
export interface About {
    id: number;
    content: string;
    image: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>
> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
