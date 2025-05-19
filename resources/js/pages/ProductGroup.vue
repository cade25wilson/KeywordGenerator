<script setup lang="ts">
import { Button } from "@/components/ui/button";
import {
  Dialog,
  DialogContent,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import ProductHolderPattern from '../components/ProductHolderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const page = usePage();
const name = ref('')

const CreateProduct = () => {
    //redirect to create product page
    router.get('/products/create', {
        group_id: page.props.productGroup.id,
    });
    // const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    //do a fetch post to product-groups sending name as name
    // fetch('/product-groups', {
    //     method: 'POST',
    //     headers: {
    //         'Content-Type': 'application/json',
    //         'X-CSRF-TOKEN': csrfToken,
    //     },
    //     body: JSON.stringify({
    //         name: name.value,
    //     }),
    // })
    // .then(response => {
    //     if (response.ok) {
    //         page.props.groups.push({
    //             name: name.value,
    //             id: response.data.id,
    //         });
    //     } else {
    //         // Handle error
    //         console.error('Error creating group');
    //     }
    // })
}
</script>

<template>
    <!-- {{ page.props }} -->
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex items-center justify-between p-4">
            <h1 class="text-5xl font-bold">
                {{ page.props.productGroup.name }}
            </h1>
            <Button @click="CreateProduct">
                Add Product
            </Button>
        </div>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3" v-if="page.props.productGroup.products.length > 0">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border" v-for="products in page.props.productGroup.products">
                    <ProductHolderPattern :product="products"/>
                </div>
            </div>
            <div class="grid auto-rows-min gap-4 md:grid-cols-3" v-else>
                <div class="flex h-full flex-col items-center justify-center gap-4 p-4 text-center">
                    <p class="text-lg font-medium text-gray-600 dark:text-gray-300">
                        Your group has no products, Click the button below to add a new product
                    </p>
                    <Button @click="CreateProduct">
                        Add Product
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
