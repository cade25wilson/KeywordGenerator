<script setup lang="ts">
import { usePage, router } from '@inertiajs/vue3';
import { Button } from "@/components/ui/button";

import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import ProductHolderPattern from '@/components/ProductHolderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const page = usePage();
const name = ref('')

const createProduct = () => {
    router.get('/products/create');
}
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex items-center justify-between p-4">
            <h1 class="text-5xl font-bold">
                Products
            </h1>
            <Button @click="createProduct">
                Add Product
            </Button>
        </div>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3" v-if="page.props.products.length > 0">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border" v-for="product in page.props.products">
                    <ProductHolderPattern :product="product"/>
                </div>
            </div>
            <div class="grid auto-rows-min gap-4 md:grid-cols-3" v-else>
                <div class="flex h-full flex-col items-center justify-center gap-4 p-4 text-center">
                    <p class="text-lg font-medium text-gray-600 dark:text-gray-300">
                        Your group has no products, Click the button below to add a new product
                    </p>
                    <Button @click="createProduct">
                        Add Product
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
