<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';

import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Button } from "@/components/ui/button";    
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel';
import { ChevronLeft, ChevronRight, Table, Trash2 } from 'lucide-vue-next';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { CardHeader, CardTitle } from '@/components/ui/card';
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from '@/components/ui/select';
import {
  Table as ShadcnTable,
  TableHeader,
  TableBody,
  TableHead,
  TableRow,
  TableCell,
TableFooter,
} from '@/components/ui/table';
import Checkbox from '@/components/ui/checkbox/Checkbox.vue';
import { Dialog, DialogTrigger, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '@/components/ui/dialog';
import { ScrollArea } from '@/components/ui/scroll-area';
import { BarChart } from '@/components/ui/chart-bar'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const page = usePage<any>();
const currentSlide = ref(0);
const totalSlides = computed(() => page.props.product.pictures.length);
const platformData = ref(JSON.parse(page.props.product.platform_data));
const keywordData = ref(JSON.parse(page.props.product.keyword_data || '[]'));
const selectedPlatform = ref('default');
const checkedGroupIds = ref<number[]>([]);

// Add missing reactive variable for keyword selection
const selectedKeywordIndex = ref(0);

// Compute the variant data based on selectedPlatform. Adjust keys if needed.
const variantData = computed({
	get() {
		switch (selectedPlatform.value) {
			case 'ebay':
				return platformData.value.ebay || {};
			case 'poshmark':
				return platformData.value.poshmark || {};
			case 'whatnot':
				return platformData.value.whatnot || {};
			case 'amazon':
				return platformData.value.amazon || {};
			default:
				return platformData.value;
		}
	},
	set(newValue) {
		// If you need two-way binding, you can assign the updates back to the right key.
		switch (selectedPlatform.value) {
			case 'ebay':
				platformData.value.ebay = newValue;
				break;
			case 'poshmark':
				platformData.value.poshmark = newValue;
				break;
			case 'whatnot':
				platformData.value.whatnot = newValue;
				break;
			case 'amazon':
				platformData.value.amazon = newValue;
				break;
			default:
				platformData.value = newValue;
		}
	}
});

// New computed property for keywords binding
const keywords = computed({
  get() {
    return (variantData.value.keywords || []).join(', ');
  },
  set(value: string) {
    variantData.value.keywords = value.split(',').map(s => s.trim());
  }
});

// Updated computed property for chart data with shorter month labels
const chartData = computed(() => {
  if (!keywordData.value || keywordData.value.length === 0) return [];
  
  const selectedKeyword = keywordData.value[selectedKeywordIndex.value];
  if (!selectedKeyword || !selectedKeyword.trend) return [];
  
  return selectedKeyword.trend.map(item => ({
    month: `${item.month.substring(0, 3)} ${item.year.toString().substring(2)}`, // Short format: "May 24"
    value: item.value
  }));
});

const selectedNotIncluded = ref<number[]>([]);
const showNotIncludedDialog = ref(false); // new ref to control dialog

// New: handler for not-included checkbox change
function onNotIncludedCheckboxChange(groupId: number, checked: boolean) {
  if (checked) {
    if (!selectedNotIncluded.value.includes(groupId)) {
      selectedNotIncluded.value.push(groupId);
    }
  } else {
    selectedNotIncluded.value = selectedNotIncluded.value.filter(id => id !== groupId);
  }
}

function onGroupCheckboxChange(groupId: number, checked: boolean) {
    if (checked) {
        if (!checkedGroupIds.value.includes(groupId)) {
            checkedGroupIds.value.push(groupId);
        }
    } else {
        checkedGroupIds.value = checkedGroupIds.value.filter(id => id !== groupId);
    }
}

async function addGroup(selectedNotIncludedIds: number[]) {
    const response = await fetch('/product-group-products/many', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': page.props.csrf_token,
        },
        body: JSON.stringify({
            product_id: page.props.product.id,
            group_ids: selectedNotIncludedIds,
        }),
    });
    
    if (response.ok) {
        router.reload();
        showNotIncludedDialog.value = false;
    }
}

 function deleteGroupFromProduct(checkedGroupIds: number[]) {
    const response = fetch('/product-group-products/many', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': page.props.csrf_token,
        },
        body: JSON.stringify({
            product_id: page.props.product.id,
            group_ids: checkedGroupIds,
        }),
    });

    response.then(res => {
        if (res.ok) {
            this.checkedGroupIds = [];
            router.reload();
        }
    });
}

async function reprocessProduct() {
    const response = await fetch(`/products/reprocess/${page.props.product.id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': page.props.csrf_token,
        },
    });

    if (response.ok) {
        router.reload();
    }
}

</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex items-center justify-between p-4">
            <h1 class="text-4xl font-bold">
                {{ page.props.product.title.charAt(0).toUpperCase() + page.props.product.title.slice(1) }} ({{ page.props.product.status.charAt(0).toUpperCase() + page.props.product.status.slice(1) }})
            </h1>
            <Button @click="reprocessProduct()" >
                Reprocess Product
            </Button>
        </div>
            <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                    <!-- Left column: Carousel -->
                    <div>

                    <Carousel class="relative wmax-w-xs">
                        <CarouselContent>
                            <CarouselItem v-for="(picture, index) in page.props.product.pictures" :key="index">
                                <div class="p-1">
                                    <Card>
                                        <CardContent class="flex aspect-square items-center justify-center p-6">
                                                        <img :src="`https://keywordgenerator2.s3.us-east-2.amazonaws.com/${picture.image_path}`" />
                                        </CardContent>
                                    </Card>
                                </div>
                            </CarouselItem>
                        </CarouselContent >
                        <CarouselPrevious class="absolute left-2 top-1/2 -translate-y-1/2 bg-white p-2 rounded shadow" v-if="currentSlide>1"/>
                        <CarouselNext class="absolute right-2 top-1/2 -translate-y-1/2 bg-white p-2 rounded shadow"  v-if="currentSlide = totalSlides -1"/>
                    </Carousel>
                    </div>
                    <!-- Right column: Card -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Select Platform</CardTitle>
                            <Select v-model="selectedPlatform">
                                <SelectTrigger>
                                    <SelectValue placeholder="Default" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="default">Default</SelectItem>
                                    <SelectItem value="ebay">eBay</SelectItem>
                                    <SelectItem value="poshmark">Poshmark</SelectItem>
                                    <SelectItem value="whatnot">Whatnot</SelectItem>
                                    <!-- ...add more platforms as needed... -->
                                </SelectContent>
                            </Select>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div>
                                    <Label>Title</Label>
                                    <!-- display the variant title -->
                                    <Input v-model="variantData.title" />
                                </div>
                                <div>
                                    <Label>Description</Label>
                                    <Textarea v-model="variantData.description" />
                                </div>
                                <div>
                                    <Label>Keywords</Label>
                                    <!-- Update v-model binding from variantData.keywords.join(', ') to keywords -->
                                    <Input v-model="keywords" />
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                
                    <!-- Third column: Card with a shadcn table of categories -->
                    <Card>
                        <CardHeader>
                            <div class="flex items-center justify-between w-full">
                                <CardTitle>Product Groups</CardTitle>
                                <span v-if="checkedGroupIds.length">
                                    <Trash2 @click="deleteGroupFromProduct(checkedGroupIds)" class="h-9 w-7 text-red-500 cursor-pointer" />
                                </span>
                                <!-- Use v-model:open for the dialog open state -->
                                <Dialog v-model:open="showNotIncludedDialog" v-else> 
                                    <DialogTrigger asChild>
                                        <Button variant="outline">Add To More Groups</Button>
                                    </DialogTrigger>
                                    <DialogContent class=" max-h-100 overflow-y-auto">
                                        <DialogHeader>
                                            <DialogTitle>Add to More Groups</DialogTitle>
                                        </DialogHeader>
                                        <!-- Wrap the table in ScrollArea with a fixed height and hide scrollbar -->
                                        <ScrollArea class="h-64 scrollbar-none">
                                            <ShadcnTable>
                                                <TableHeader>
                                                    <TableRow>
                                                        <TableHead class="text-center"></TableHead>
                                                        <TableHead class="text-center">Name</TableHead>
                                                    </TableRow>
                                                </TableHeader>
                                                <TableBody>
                                                    <TableRow
                                                        v-for="group in page.props.notIncludedGroups"
                                                        :key="group.id"
                                                    >
                                                        <TableCell class="text-center">
                                                            <Checkbox
                                                                :modelValue="selectedNotIncluded.includes(group.id)"
                                                                @update:modelValue="value => onNotIncludedCheckboxChange(group.id, value)"
                                                            />
                                                        </TableCell>
                                                        <TableCell class="text-center">{{ group.name }}</TableCell>
                                                    </TableRow>
                                                </TableBody>
                                            </ShadcnTable>
                                        </ScrollArea>
                                        <DialogFooter>
                                            <Button @click="addGroup(selectedNotIncluded)" class="ml-auto">
                                                Add
                                            </Button>
                                        </DialogFooter>
                                    </DialogContent>
                                </Dialog>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <ScrollArea class="h-64">
                                <ShadcnTable>
                                    <TableHeader>
                                        <TableRow>
                                            <TableHead class="w-8 text-center"></TableHead>
                                            <TableHead class="text-center">Group</TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        <TableRow
                                            v-for="(group, index) in page.props.product.product_groups"
                                            :key="index"
                                        >
                                            <TableCell class="text-center">
                                                <Checkbox
                                                    :modelValue="checkedGroupIds.includes(group.id)"
                                                    @update:modelValue="checked => onGroupCheckboxChange(group.id, checked)"
                                                />
                                            </TableCell>
                                            <TableCell class="text-center">{{ group.name }}</TableCell>
                                        </TableRow>
                                    </TableBody>
                                </ShadcnTable>
                            </ScrollArea>
                        </CardContent>
                    </Card>
                </div>
                <div class="flex flex-col gap-4">
                    <Card>
                        <CardHeader>
                        <CardTitle>Keyword Trends</CardTitle>
                        <Select v-model="selectedKeywordIndex">
                            <SelectTrigger>
                              <SelectValue placeholder="Select Keyword" />
                            </SelectTrigger>
                            <SelectContent>
                              <SelectItem 
                                v-for="(kw, index) in keywordData" 
                                :key="kw.keyword" 
                                :value="Number(index)">
                                {{ kw.keyword }}
                              </SelectItem>
                            </SelectContent>
                        </Select>
                        </CardHeader>
                        <CardContent>
                        <div class="relative">
                          <BarChart
                              index="month"
                              :data="chartData"
                              :categories="['value']"
                              :rounded-corners="4"
                              :colors="['#fffff']"
                              :show-x-axis="true"
                              :show-y-axis="true"
                              :show-grid-lines="true"
                              :tick-label-props="{
                                fontSize: 11,
                                angle: -45,
                                textAnchor: 'end',
                                dy: -4
                              }"
                          />
                          <!-- Y-axis label -->
                          <div class="absolute -left-4 top-1/2 transform -translate-y-1/2 -rotate-90 text-sm text-muted-foreground font-medium">
                            Search Volume
                          </div>
                          <!-- X-axis label -->
                          <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 text-sm text-muted-foreground font-medium">
                            Month
                          </div>
                        </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
    </AppLayout>
</template>