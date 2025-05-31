<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
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

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const page = usePage();
const name = ref('')
const showDialog = ref(false);

const createGroup = () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    //do a fetch post to product-groups sending name as name
    fetch('/product-groups', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({
            name: name.value,
        }),
    })
    .then(response => {
        if (response.ok) {
            // page.props.groups.push({
            //     name: name.value,
            //     id: response.data.id,
            // });
            // close the dialog
            name.value = '';
            router.reload();
            showDialog.value = false;
        } else {
            // Handle error
            console.error('Error creating group');
        }
    })
}
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <!-- Header: Wrap the Add Group button with a Dialog -->
        <div class="flex items-center justify-between p-4">
            <h1 class="text-4xl font-bold">
                Product Groups
            </h1>
            <Dialog v-model:open="showDialog">
                <DialogTrigger asChild>
                    <Button class="cursor-pointer">
                        Add Group
                    </Button>
                </DialogTrigger>
                <DialogContent class="sm:max-w-[425px]">
                    <DialogHeader>
                        <DialogTitle>Create Group</DialogTitle>
                    </DialogHeader>
                    <div class="grid gap-4 py-4">
                        <div class="grid grid-cols-4 items-center gap-4">
                            <Label for="name" class="text-right">
                                Name
                            </Label>
                            <Input id="name" class="col-span-3" v-model="name" @keyup.enter="createGroup()"/>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="submit" class="cursor-pointer" @click="createGroup()">Save changes</Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>

        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3" v-if="page.props.groups.length > 0">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border" v-for="group in page.props.groups">
                    <PlaceholderPattern :group="group"/>
                </div>
            </div>
            <!-- Remove the duplicate dialog trigger when there are no groups -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-3" v-else>
                <div class="flex h-full flex-col items-center justify-center gap-4 p-4 text-center">
                    <p class="text-lg font-medium text-gray-600 dark:text-gray-300">
                        You currently have no groups. Create one to get started!
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
