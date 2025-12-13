<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/components/ui/popover';
import { toast } from 'vue-sonner';

interface Producto {
  id: number;
  codigo: string;
  nombre: string;
  presentacion_tipo: 'unidad' | 'peso';
  presentacion_valor: string | null;
  imagen: string | null;
  valor_costo: number;
  valor_venta: number;
  marca: string | null;
  observaciones: string | null;
  created_at: string;
  updated_at: string;
}

interface PaginatedData {
  data: Producto[];
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
  links: Array<{
    url: string | null;
    label: string;
    active: boolean;
  }>;
}

const props = defineProps<{
  productos: PaginatedData;
  filters: {
    codigo?: string;
    nombre?: string;
    marca?: string;
    presentacion_tipo?: string;
  };
}>();

const isCreateDialogOpen = ref(false);
const isEditDialogOpen = ref(false);
const isDeleteDialogOpen = ref(false);
const isImageDialogOpen = ref(false);
const selectedProducto = ref<Producto | null>(null);
const selectedImage = ref<string | null>(null);
const imagePreview = ref<string | null>(null);

// Filtros
const filterForm = ref({
  codigo: props.filters.codigo || '',
  nombre: props.filters.nombre || '',
  marca: props.filters.marca || '',
  presentacion_tipo: props.filters.presentacion_tipo || '',
});

const createForm = useForm({
  codigo: '',
  nombre: '',
  presentacion_tipo: 'unidad',
  presentacion_valor: '',
  imagen: null as File | null,
  valor_costo: '',
  valor_venta: '',
  marca: '',
  observaciones: '',
});

const editForm = useForm({
  codigo: '',
  nombre: '',
  presentacion_tipo: 'unidad' as 'unidad' | 'peso',
  presentacion_valor: '',
  imagen: null as File | null,
  valor_costo: '',
  valor_venta: '',
  marca: '',
  observaciones: '',
  _method: 'PUT',
});

// Watch for flash messages
watch(() => props, (newProps: any) => {
  if (newProps.$page?.props?.flash?.success) {
    toast.success(newProps.$page.props.flash.success);
  }
}, { deep: true, immediate: true });

const handleImageChange = (event: Event, isEdit: boolean = false) => {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];
  
  if (file) {
    const form = isEdit ? editForm : createForm;
    form.imagen = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target?.result as string;
    };
    reader.readAsDataURL(file);
  }
};

const applyFilters = () => {
  router.get('/productos', filterForm.value, {
    preserveState: true,
    preserveScroll: true,
  });
};

const clearFilters = () => {
  filterForm.value = {
    codigo: '',
    nombre: '',
    marca: '',
    presentacion_tipo: '',
  };
  router.get('/productos');
};

const openCreateDialog = () => {
  createForm.reset();
  imagePreview.value = null;
  isCreateDialogOpen.value = true;
};

const openEditDialog = (producto: Producto) => {
  selectedProducto.value = producto;
  editForm.codigo = producto.codigo;
  editForm.nombre = producto.nombre;
  editForm.presentacion_tipo = producto.presentacion_tipo;
  editForm.presentacion_valor = producto.presentacion_valor || '';
  editForm.valor_costo = producto.valor_costo.toString();
  editForm.valor_venta = producto.valor_venta.toString();
  editForm.marca = producto.marca || '';
  editForm.observaciones = producto.observaciones || '';
  editForm.imagen = null;
  imagePreview.value = producto.imagen ? `/storage/${producto.imagen}` : null;
  isEditDialogOpen.value = true;
};

const confirmDelete = (producto: Producto) => {
  selectedProducto.value = producto;
  isDeleteDialogOpen.value = true;
};

const viewImage = (producto: Producto) => {
  if (producto.imagen) {
    selectedImage.value = `/storage/${producto.imagen}`;
    isImageDialogOpen.value = true;
  }
};

const submitCreate = () => {
  createForm.post('/productos', {
    forceFormData: true,
    onSuccess: () => {
      isCreateDialogOpen.value = false;
      createForm.reset();
      imagePreview.value = null;
    },
  });
};

const submitEdit = () => {
  if (!selectedProducto.value) return;
  
  editForm.post(`/productos/${selectedProducto.value.id}`, {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      isEditDialogOpen.value = false;
      editForm.reset();
      imagePreview.value = null;
      selectedProducto.value = null;
    },
  });
};

const deleteProducto = () => {
  if (!selectedProducto.value) return;
  
  router.delete(`/productos/${selectedProducto.value.id}`, {
    preserveScroll: true,
    onSuccess: () => {
      isDeleteDialogOpen.value = false;
      selectedProducto.value = null;
    },
  });
};

const goToPage = (url: string | null) => {
  if (url) {
    router.get(url, {}, { preserveState: true, preserveScroll: true });
  }
};

const exportPdf = () => {
  window.open('/productos/export/pdf', '_blank');
};

const exportExcel = () => {
  window.open('/productos/export/excel', '_blank');
};

const formatCurrency = (value: number) => {
  return new Intl.NumberFormat('es-CO', {
    style: 'currency',
    currency: 'COP',
    minimumFractionDigits: 0,
  }).format(value);
};
</script>

<template>
  <AppLayout>
    <Head title="Productos" />

    <div class="container mx-auto py-8 px-4 space-y-6">
      <!-- Header Card -->
      <Card>
        <CardHeader>
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
              <CardTitle class="text-3xl">Gestión de Productos</CardTitle>
              <CardDescription>Administra tu inventario de productos</CardDescription>
            </div>
            <div class="flex gap-2">
              <!-- Filtros -->
              <Popover>
                <PopoverTrigger as-child>
                  <Button variant="outline" size="sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filtros
                  </Button>
                </PopoverTrigger>
                <PopoverContent class="w-80">
                  <div class="space-y-4">
                    <h4 class="font-medium leading-none">Filtrar productos</h4>
                    
                    <div class="space-y-2">
                      <Label for="filter-codigo">Código</Label>
                      <Input id="filter-codigo" v-model="filterForm.codigo" placeholder="Buscar por código" />
                    </div>

                    <div class="space-y-2">
                      <Label for="filter-nombre">Nombre</Label>
                      <Input id="filter-nombre" v-model="filterForm.nombre" placeholder="Buscar por nombre" />
                    </div>

                    <div class="space-y-2">
                      <Label for="filter-marca">Marca</Label>
                      <Input id="filter-marca" v-model="filterForm.marca" placeholder="Buscar por marca" />
                    </div>

                    <div class="space-y-2">
                      <Label for="filter-tipo">Tipo de Presentación</Label>
                      <select id="filter-tipo" v-model="filterForm.presentacion_tipo" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                        <option value="">Todos</option>
                        <option value="unidad">Unidad</option>
                        <option value="peso">Peso</option>
                      </select>
                    </div>

                    <div class="flex gap-2">
                      <Button @click="applyFilters" class="flex-1">Aplicar</Button>
                      <Button @click="clearFilters" variant="outline" class="flex-1">Limpiar</Button>
                    </div>
                  </div>
                </PopoverContent>
              </Popover>

              <Button @click="exportPdf" variant="outline" size="sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                </svg>
                PDF
              </Button>
              <Button @click="exportExcel" variant="outline" size="sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Excel
              </Button>
              <Button @click="openCreateDialog">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nuevo Producto
              </Button>
            </div>
          </div>
        </CardHeader>
        <CardContent>
          <!-- Table -->
          <div class="rounded-md border">
            <div class="relative w-full overflow-x-auto">
              <table class="w-full caption-bottom text-sm">
                <thead class="[&_tr]:border-b">
                  <tr class="border-b transition-colors hover:bg-muted/50">
                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Código</th>
                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nombre</th>
                    <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Imagen</th>
                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Presentación</th>
                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Marca</th>
                    <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Costo</th>
                    <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Venta</th>
                    <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Acciones</th>
                  </tr>
                </thead>
                <tbody class="[&_tr:last-child]:border-0">
                  <tr v-if="productos.data.length === 0" class="border-b transition-colors hover:bg-muted/50">
                    <td colspan="8" class="p-8 text-center text-muted-foreground">
                      No se encontraron productos
                    </td>
                  </tr>
                  <tr v-for="producto in productos.data" :key="producto.id" class="border-b transition-colors hover:bg-muted/50">
                    <td class="p-4 align-middle font-medium">{{ producto.codigo }}</td>
                    <td class="p-4 align-middle">{{ producto.nombre }}</td>
                    <td class="p-4 align-middle text-center">
                      <Button 
                        v-if="producto.imagen" 
                        @click="viewImage(producto)" 
                        variant="ghost" 
                        size="icon"
                        class="h-8 w-8"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                      </Button>
                      <span v-else class="text-muted-foreground text-xs">-</span>
                    </td>
                    <td class="p-4 align-middle">
                      <Badge variant="secondary">
                        {{ producto.presentacion_tipo }}
                        {{ producto.presentacion_valor ? ` - ${producto.presentacion_valor}` : '' }}
                      </Badge>
                    </td>
                    <td class="p-4 align-middle">{{ producto.marca || '-' }}</td>
                    <td class="p-4 align-middle text-right">{{ formatCurrency(producto.valor_costo) }}</td>
                    <td class="p-4 align-middle text-right">{{ formatCurrency(producto.valor_venta) }}</td>
                    <td class="p-4 align-middle">
                      <div class="flex items-center justify-center gap-2">
                        <Button @click="openEditDialog(producto)" variant="ghost" size="icon">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                          </svg>
                        </Button>
                        <Button @click="confirmDelete(producto)" variant="ghost" size="icon" class="text-destructive">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                          </svg>
                        </Button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Pagination -->
          <div class="flex items-center justify-between mt-4">
            <div class="text-sm text-muted-foreground">
              Mostrando {{ productos.data.length }} de {{ productos.total }} productos
            </div>
            <div class="flex gap-2">
              <Button
                v-for="link in productos.links"
                :key="link.label"
                @click="goToPage(link.url)"
                :variant="link.active ? 'default' : 'outline'"
                :disabled="!link.url"
                size="sm"
                v-html="link.label"
              />
            </div>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Create Dialog -->
    <Dialog v-model:open="isCreateDialogOpen">
      <DialogContent class="max-w-2xl max-h-[90vh] overflow-y-auto">
        <DialogHeader>
          <DialogTitle>Crear Nuevo Producto</DialogTitle>
          <DialogDescription>Completa el formulario para agregar un producto</DialogDescription>
        </DialogHeader>
        
        <form @submit.prevent="submitCreate" class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-2">
              <Label for="create-codigo">Código *</Label>
              <Input id="create-codigo" v-model="createForm.codigo" required placeholder="PROD001" />
              <p v-if="createForm.errors.codigo" class="text-sm text-destructive">{{ createForm.errors.codigo }}</p>
            </div>

            <div class="space-y-2">
              <Label for="create-nombre">Nombre *</Label>
              <Input id="create-nombre" v-model="createForm.nombre" required />
              <p v-if="createForm.errors.nombre" class="text-sm text-destructive">{{ createForm.errors.nombre }}</p>
            </div>

            <div class="space-y-2">
              <Label for="create-tipo">Tipo de Presentación *</Label>
              <select id="create-tipo" v-model="createForm.presentacion_tipo" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                <option value="unidad">Unidad</option>
                <option value="peso">Peso</option>
              </select>
            </div>

            <div class="space-y-2">
              <Label for="create-valor-presentacion">Valor Presentación</Label>
              <Input id="create-valor-presentacion" v-model="createForm.presentacion_valor" placeholder="500g, 1L" />
            </div>

            <div class="space-y-2">
              <Label for="create-costo">Valor Costo *</Label>
              <Input id="create-costo" v-model="createForm.valor_costo" type="number" step="0.01" required />
              <p v-if="createForm.errors.valor_costo" class="text-sm text-destructive">{{ createForm.errors.valor_costo }}</p>
            </div>

            <div class="space-y-2">
              <Label for="create-venta">Valor Venta *</Label>
              <Input id="create-venta" v-model="createForm.valor_venta" type="number" step="0.01" required />
              <p v-if="createForm.errors.valor_venta" class="text-sm text-destructive">{{ createForm.errors.valor_venta }}</p>
            </div>

            <div class="space-y-2 col-span-2">
              <Label for="create-marca">Marca</Label>
              <Input id="create-marca" v-model="createForm.marca" />
            </div>

            <div class="space-y-2 col-span-2">
              <Label for="create-imagen">Imagen</Label>
              <div class="flex gap-4">
                <Input id="create-imagen" type="file" accept="image/*" @change="handleImageChange" class="flex-1" />
                <div v-if="imagePreview" class="w-20 h-20 rounded border overflow-hidden">
                  <img :src="imagePreview" alt="Preview" class="w-full h-full object-cover" />
                </div>
              </div>
            </div>

            <div class="space-y-2 col-span-2">
              <Label for="create-observaciones">Observaciones</Label>
              <textarea id="create-observaciones" v-model="createForm.observaciones" rows="3" class="flex min-h-20 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"></textarea>
            </div>
          </div>

          <DialogFooter>
            <Button type="button" variant="outline" @click="isCreateDialogOpen = false">Cancelar</Button>
            <Button type="submit" :disabled="createForm.processing">
              {{ createForm.processing ? 'Guardando...' : 'Guardar' }}
            </Button>
          </DialogFooter>
        </form>
      </DialogContent>
    </Dialog>

    <!-- Edit Dialog -->
    <Dialog v-model:open="isEditDialogOpen">
      <DialogContent class="max-w-2xl max-h-[90vh] overflow-y-auto">
        <DialogHeader>
          <DialogTitle>Editar Producto</DialogTitle>
          <DialogDescription>¿Estás seguro de que deseas modificar este producto?</DialogDescription>
        </DialogHeader>
        
        <form @submit.prevent="submitEdit" class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-2">
              <Label for="edit-codigo">Código *</Label>
              <Input id="edit-codigo" v-model="editForm.codigo" required />
              <p v-if="editForm.errors.codigo" class="text-sm text-destructive">{{ editForm.errors.codigo }}</p>
            </div>

            <div class="space-y-2">
              <Label for="edit-nombre">Nombre *</Label>
              <Input id="edit-nombre" v-model="editForm.nombre" required />
              <p v-if="editForm.errors.nombre" class="text-sm text-destructive">{{ editForm.errors.nombre }}</p>
            </div>

            <div class="space-y-2">
              <Label for="edit-tipo">Tipo de Presentación *</Label>
              <select id="edit-tipo" v-model="editForm.presentacion_tipo" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                <option value="unidad">Unidad</option>
                <option value="peso">Peso</option>
              </select>
            </div>

            <div class="space-y-2">
              <Label for="edit-valor-presentacion">Valor Presentación</Label>
              <Input id="edit-valor-presentacion" v-model="editForm.presentacion_valor" />
            </div>

            <div class="space-y-2">
              <Label for="edit-costo">Valor Costo *</Label>
              <Input id="edit-costo" v-model="editForm.valor_costo" type="number" step="0.01" required />
              <p v-if="editForm.errors.valor_costo" class="text-sm text-destructive">{{ editForm.errors.valor_costo }}</p>
            </div>

            <div class="space-y-2">
              <Label for="edit-venta">Valor Venta *</Label>
              <Input id="edit-venta" v-model="editForm.valor_venta" type="number" step="0.01" required />
              <p v-if="editForm.errors.valor_venta" class="text-sm text-destructive">{{ editForm.errors.valor_venta }}</p>
            </div>

            <div class="space-y-2 col-span-2">
              <Label for="edit-marca">Marca</Label>
              <Input id="edit-marca" v-model="editForm.marca" />
            </div>

            <div class="space-y-2 col-span-2">
              <Label for="edit-imagen">Imagen</Label>
              <div class="flex gap-4">
                <div class="flex-1 space-y-1">
                  <Input id="edit-imagen" type="file" accept="image/*" @change="(e: Event) => handleImageChange(e, true)" />
                  <p class="text-xs text-muted-foreground">Deja vacío para mantener la imagen actual</p>
                </div>
                <div v-if="imagePreview" class="w-20 h-20 rounded border overflow-hidden">
                  <img :src="imagePreview" alt="Preview" class="w-full h-full object-cover" />
                </div>
              </div>
            </div>

            <div class="space-y-2 col-span-2">
              <Label for="edit-observaciones">Observaciones</Label>
              <textarea id="edit-observaciones" v-model="editForm.observaciones" rows="3" class="flex min-h-20 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"></textarea>
            </div>
          </div>

          <DialogFooter>
            <Button type="button" variant="outline" @click="isEditDialogOpen = false">Cancelar</Button>
            <Button type="submit" :disabled="editForm.processing">
              {{ editForm.processing ? 'Actualizando...' : 'Confirmar y Actualizar' }}
            </Button>
          </DialogFooter>
        </form>
      </DialogContent>
    </Dialog>

    <!-- Delete Confirmation Dialog -->
    <Dialog v-model:open="isDeleteDialogOpen">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Confirmar Eliminación</DialogTitle>
          <DialogDescription>
            ¿Estás seguro de que deseas eliminar el producto "{{ selectedProducto?.nombre }}"? Esta acción no se puede deshacer.
          </DialogDescription>
        </DialogHeader>
        <DialogFooter>
          <Button variant="outline" @click="isDeleteDialogOpen = false">Cancelar</Button>
          <Button variant="destructive" @click="deleteProducto">Eliminar</Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>

    <!-- Image View Dialog -->
    <Dialog v-model:open="isImageDialogOpen">
      <DialogContent class="max-w-3xl">
        <DialogHeader>
          <DialogTitle>Imagen del Producto</DialogTitle>
        </DialogHeader>
        <div class="flex items-center justify-center p-4">
          <img v-if="selectedImage" :src="selectedImage" alt="Producto" class="max-w-full max-h-[70vh] rounded-lg" />
        </div>
      </DialogContent>
    </Dialog>
  </AppLayout>
</template>
