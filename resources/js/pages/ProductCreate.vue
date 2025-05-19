<script setup lang="ts">
import { Head, useForm, router, usePage } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { ref } from 'vue';

//get group_id from url if it doesnt exist set it to null
const urlParams = new URLSearchParams(window.location.search);
const groupId = urlParams.get('group_id');
const group_id = ref<number | null>(groupId ? parseInt(groupId) : null);
const form = useForm({
    title: '',
    images: [] as File[],
    group_id: group_id.value,
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Create Product', href: '/products/create' },
];

const handleFiles = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files) {
        form.images = Array.from(target.files);
    }
};

const page = usePage();

const successMessage = ref('');

// Watch for Inertia flash message after redirect
if (page.props.flash && page.props.flash.success) {
    successMessage.value = page.props.flash.success;
    setTimeout(() => {
        successMessage.value = '';
    }, 1000);
}

const submit = () => {
    form.post('/products', {
        forceFormData: true,
        onSuccess: () => {
            if (group_id.value) {
                successMessage.value = 'Product created!';
            }
            setTimeout(() => {
                successMessage.value = '';
            }, 4000);
        },
        onError: () => {
            // handle errors if needed
        },
    });
};

const goToDashboard = () => {
    router.visit('/dashboard');
};

const goToGroup = () => {
    if (group_id.value) {
        router.visit(`/product-groups/${group_id.value}`);
    }
};
</script>

<template>
    <Head title="Create Product" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 max-w-xl mx-auto">
            <div v-if="successMessage" class="space-y-4 text-center">
                <p class="text-lg font-semibold text-black dark:text-white">{{ successMessage }}</p>
            </div>
            <h1 class="text-2xl font-bold mb-4">Create Product</h1>
            <form @submit.prevent="submit" class="space-y-6" >
                <div>
                    <Label for="title">Title</Label>
                    <Input
                        id="title"
                        v-model="form.title"
                        type="text"
                        placeholder="Product title"
                        maxlength="255"
                        class="mt-1"
                    />
                    <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</div>
                </div>
                <div>
                    <Label for="images">Images</Label>
                    <Input
                        id="images"
                        type="file"
                        multiple
                        accept="image/*"
                        @change="handleFiles"
                        class="mt-1"
                    />
                    <div v-if="form.errors.images" class="text-red-500 text-sm mt-1">{{ form.errors.images }}</div>
                    <div v-if="form.errors['images.0']" class="text-red-500 text-sm mt-1">{{ form.errors['images.0'] }}</div>
                </div>
                <Button type="submit" :disabled="form.processing">Create</Button>
            </form>
        </div>
    </AppLayout>
</template>
