# Gyakorló Cart - Teljes Projekt

## 📋 Projekt Indítása

### Backend és Frontend indítása

```bash
bash start.sh
php artisan migrate:fresh --seed
```

## 🛠️ Implementációs Lépések

### 1. Products Store létrehozása

Hozzunk létre egy új store-t a termékek kezelésére.

**`ProductsStore.mjs`:**

```javascript
import { api } from '@utils/http.mjs';
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useProducts = defineStore('products', () => {
    const products = ref([]);
    const isLoading = ref(true);

    async function load() {
        const res = await api.get("products");
        products.value = res.data.data;
        isLoading.value = false;
    }
    load();

    return {products, isLoading};
});
```

### 2. Products Store importálása

**`/pages/index.vue`** - Import hozzáadása:

```javascript
import { useProducts } from '@stores/ProductsStore.mjs';
const products = useProducts()
```

### 3. HTTP Konfigurálás

**Opció 1 - `http.mjs` módosítása:**

```javascript
import axios from 'axios'

export const api = axios.create({
    baseURL: import.meta.env.VITE_BACKEND_URL + "/api",
    headers:{
        "Accept": "application/json",
        "Content-Type": "application/json" 
    }
})
```

**Opció 2 - Environment változók:**

> ENV fájlban kell állítani a megfelelő API endpoint-ot!

### 4. ShadCN Card komponens telepítése

```bash
docker compose exec frontend fish
pnpm dlx shadcn-vue@latest add card
```

**Vagy:**

```bash
docker compose exec frontend pnpm dlx shadcn-vue@latest add card
```

### 5. Termékek megjelenítése - Alap template

**`/pages/index.vue`** - Kezdő sablon:

```vue
<template>
  <BaseLayout>
    <h1 class="text-6xl my-10">Termékek</h1>
    <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-4">
      
    </div>
  </BaseLayout>
</template>
```

### 6. Termékek megjelenítése - Card komponensekkel

**`/pages/index.vue`** - Teljes implementáció:

```vue
<script setup>
import BaseLayout from '@layouts/BaseLayout.vue'
import { useCounter } from '@stores/CounterStore.mjs'
import { useProducts } from '@stores/ProductsStore.mjs';

import {
  Card,
  CardAction,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"

const counter = useCounter()
const ProductsStore = useProducts()

const RED_PRICE_LIMIT = 1800
</script>

<template>
  <BaseLayout>
    <h1 class="text-6xl my-10">Termékek</h1>
    <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-4">
      <Card v-for="p of ProductsStore.products">
        <CardHeader>
          <CardTitle>{{ p.name }}</CardTitle>
        </CardHeader>
        <CardContent>
          <p>{{ p.description }}</p>
          <p>{{ p.size_cm }} cm</p>
          <p class="text-xl font-bold text-right"
          :class="{ 'text-red-500': Number.parseFloat(p.price) < RED_PRICE_LIMIT }">{{ Number.parseFloat(p.price) }} Ft</p>
        </CardContent>
      </Card>
    </div>
  </BaseLayout>
</template>

<route lang="yaml">
name: index
meta:
  title: Főoldal
</route>
```

### 7. Tesztelés

> **Fontos:** Seeder, phpMyAdmin vagy Swagger használatával módosítsd az árakat, hogy teszteld a piros színezést!
> 
> Legyen olyan termék, ami:
> - Kisebb az árlimitnél
> - Egyenlő az árlimittel  
> - Nagyobb az árlimitnél
>
> 📸 Képernyőképen elég egy oldal az árakkal!

### 8. Button komponens telepítése

```bash
docker compose exec frontend pnpm dlx shadcn-vue@latest add button
```

### 9. "Kosárba teszem" gomb hozzáadása

**`/pages/index.vue`** - Frissített verzió gombbal:

```vue
<script setup>
import BaseLayout from '@layouts/BaseLayout.vue'
import { useCounter } from '@stores/CounterStore.mjs'
import { useProducts } from '@stores/ProductsStore.mjs';
import { Button } from "@/components/ui/button";

import {
  Card,
  CardAction,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"

const counter = useCounter()
const ProductsStore = useProducts()

const RED_PRICE_LIMIT = 1800
</script>

<template>
  <BaseLayout>
    <h1 class="text-6xl my-10">Termékek</h1>
    <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-4">
      <Card v-for="p of ProductsStore.products">
        <CardHeader>
          <CardTitle>{{ p.name }}</CardTitle>
        </CardHeader>
        <CardContent>
          <p>{{ p.description }}</p>
          <p>{{ p.size_cm }} cm</p>
          <p class="text-xl font-bold text-right"
          :class="{ 'text-red-500': Number.parseFloat(p.price) < RED_PRICE_LIMIT }">{{ Number.parseFloat(p.price) }} Ft</p>
        </CardContent>
        <CardFooter>
          <Button class="w-full">Kosárba teszem</Button>
        </CardFooter>
      </Card>
    </div>
  </BaseLayout>
</template>

<route lang="yaml">
name: index
meta:
  title: Főoldal
</route>
```

---

## 🛒 Kosár Funkció Implementálása

> **Nagyjából ennyi a dolgozat! 😁**
> 
> **DE VAN MÉG FELADAT!**

### 10. Sheet komponens használata - Kosár megjelenítés

#### Sheet komponens telepítése

```bash
docker compose exec frontend pnpm dlx shadcn-vue@latest add sheet
```

#### BaseHeader.vue módosítása

**`/src/components/layout/BaseHeader.vue`** - Kosár gomb hozzáadása:

```vue
<script setup>
import {
  NavigationMenu,
  NavigationMenuLink,
  NavigationMenuList,
  NavigationMenuItem,
  navigationMenuTriggerStyle
} from '@components/ui/navigation-menu'
import {
  Sheet,
  SheetContent,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
} from "@/components/ui/sheet"

import { Button } from "@/components/ui/button"

const title = import.meta.env.VITE_APP_NAME

const links = [
  {
    label: 'Page',
    to: '#'
  }
]
</script>

<template>
  <header class="bg-white">
    <div class="flex justify-between p-3 border-b-2 flex-wrap">
      <RouterLink to="/" class="flex items-center space-x-3">
        <span class="self-center text-2xl font-semibold">{{ title }}</span>
      </RouterLink>
      <Sheet>
        <SheetTrigger asChild>
          <button variant="outline" size="icon" class="lg:hidden">
            <svg
              class="h-6 w-6"
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth="2"
              strokeLinecap="round"
              strokeLinejoin="round"
            >
              <line x1="4" x2="20" y1="12" y2="12" />
              <line x1="4" x2="20" y1="6" y2="6" />
              <line x1="4" x2="20" y1="18" y2="18" />
            </svg>
            <span class="sr-only">Navigációs menü</span>
          </button>
        </SheetTrigger>
        <SheetContent side="left">
          <RouterLink to="/" class="mr-6 hidden lg:flex">
            <span class="sr-only">{{ title }}</span>
          </RouterLink>
          <div class="grid gap-2 py-6">
            <RouterLink
              v-for="link of links"
              :key="link.to"
              :to="link.to"
              class="flex w-full items-center py-2 text-lg font-semibold"
            >
              {{ link.label }}
            </RouterLink>
          </div>
        </SheetContent>
      </Sheet>
      <NavigationMenu class="hidden lg:block">
        <NavigationMenuList>
          <NavigationMenuItem v-for="link of links" :key="link.to">
            <RouterLink v-slot="{ isActive, href, navigate }" :to="link.to" custom>
              <NavigationMenuLink
                :active="isActive"
                :href
                :class="navigationMenuTriggerStyle()"
                @click="navigate"
              >
                {{ link.label }}
              </NavigationMenuLink>
            </RouterLink>
          </NavigationMenuItem>
        </NavigationMenuList>
      </NavigationMenu>
      <Sheet>
        <SheetTrigger asChild>
          <Button>Kosár</Button>
        </SheetTrigger>
        <SheetContent>
          <SheetHeader>
            <SheetTitle>Kosár</SheetTitle>
          </SheetHeader>
        </SheetContent>
      </Sheet>
    </div>
  </header>
</template>
```

### 11. Cart Store létrehozása

**`CartStore.mjs`:**

```javascript
import { defineStore } from "pinia";
import { ref } from "vue";

export const useCart = defineStore("cart", () => {
    const productsInCart = ref([]);
    
    function addToCart(product){
        productsInCart.value.push(product);
    }

    return{
        productsInCart,
        addToCart,
    };
});
```

### 12. Kosárba rakás funkció - index.vue frissítése

**`/pages/index.vue`** - Kosár funkcióval:

```vue
<script setup>
import BaseLayout from '@layouts/BaseLayout.vue'
import { useCounter } from '@stores/CounterStore.mjs'
import { useProducts } from '@stores/ProductsStore.mjs';
import { useCart } from '@stores/CartStore.mjs';
import { Button } from "@/components/ui/button";

import {
  Card,
  CardAction,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"

const counter = useCounter()
const ProductsStore = useProducts()
const cart = useCart()

const RED_PRICE_LIMIT = 1800
</script>

<template>
  <BaseLayout>
    <h1 class="text-6xl my-10">Termékek</h1>
    <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-4">
      <Card v-for="p of ProductsStore.products">
        <CardHeader>
          <CardTitle>{{ p.name }}</CardTitle>
        </CardHeader>
        <CardContent>
          <p>{{ p.description }}</p>
          <p>{{ p.size_cm }} cm</p>
          <p class="text-xl font-bold text-right"
          :class="{ 'text-red-500': Number.parseFloat(p.price) < RED_PRICE_LIMIT }">{{ Number.parseFloat(p.price) }} Ft</p>
        </CardContent>
        <CardFooter>
          <Button class="w-full" @click="cart.addToCart(p)">Kosárba teszem</Button>
        </CardFooter>
      </Card>
    </div>
  </BaseLayout>
</template>

<route lang="yaml">
name: index
meta:
  title: Főoldal
</route>
```

### 13. BaseHeader frissítése - Kosár megjelenítéssel

**`/src/components/layout/BaseHeader.vue`** - Teljes verzió kosár tartalommal:

```vue
<script setup>
import {
  NavigationMenu,
  NavigationMenuLink,
  NavigationMenuList,
  NavigationMenuItem,
  navigationMenuTriggerStyle
} from '@components/ui/navigation-menu'
import {
  Sheet,
  SheetContent,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
} from "@/components/ui/sheet"

import { Button } from "@/components/ui/button"
import { useCart } from '@stores/CartStore.mjs'

const title = import.meta.env.VITE_APP_NAME

const links = [
  {
    label: 'Page',
    to: '#'
  }
]

const cart = useCart()
</script>

<template>
  <header class="bg-white">
    <div class="flex justify-between p-3 border-b-2 flex-wrap">
      <RouterLink to="/" class="flex items-center space-x-3">
        <span class="self-center text-2xl font-semibold">{{ title }}</span>
      </RouterLink>
      <Sheet>
        <SheetTrigger asChild>
          <button variant="outline" size="icon" class="lg:hidden">
            <svg
              class="h-6 w-6"
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth="2"
              strokeLinecap="round"
              strokeLinejoin="round"
            >
              <line x1="4" x2="20" y1="12" y2="12" />
              <line x1="4" x2="20" y1="6" y2="6" />
              <line x1="4" x2="20" y1="18" y2="18" />
            </svg>
            <span class="sr-only">Navigációs menü</span>
          </button>
        </SheetTrigger>
        <SheetContent side="left">
          <RouterLink to="/" class="mr-6 hidden lg:flex">
            <span class="sr-only">{{ title }}</span>
          </RouterLink>
          <div class="grid gap-2 py-6">
            <RouterLink
              v-for="link of links"
              :key="link.to"
              :to="link.to"
              class="flex w-full items-center py-2 text-lg font-semibold"
            >
              {{ link.label }}
            </RouterLink>
          </div>
        </SheetContent>
      </Sheet>
      <NavigationMenu class="hidden lg:block">
        <NavigationMenuList>
          <NavigationMenuItem v-for="link of links" :key="link.to">
            <RouterLink v-slot="{ isActive, href, navigate }" :to="link.to" custom>
              <NavigationMenuLink
                :active="isActive"
                :href
                :class="navigationMenuTriggerStyle()"
                @click="navigate"
              >
                {{ link.label }}
              </NavigationMenuLink>
            </RouterLink>
          </NavigationMenuItem>
        </NavigationMenuList>
      </NavigationMenu>
      <Sheet>
        <SheetTrigger asChild>
          <Button>Kosár ({{ cart.productsInCart.length }})</Button>
        </SheetTrigger>
        <SheetContent>
          <SheetHeader>
            <SheetTitle>Kosár</SheetTitle>
          </SheetHeader>
          <div class="px-4">
            <div v-for="p of cart.productsInCart">{{ p.name }}</div>
          </div>
        </SheetContent>
      </Sheet>
    </div>
  </header>
</template>
```

## 💾 Kosár Perzisztencia - Adatok megőrzése

### 14. Pinia Plugin Persistedstate

**Ne tűnjön el a kosárban az adat újratöltés után!**

📚 **Dokumentáció:** [pinia-plugin-persistedstate](https://www.npmjs.com/package/pinia-plugin-persistedstate)

#### Telepítés

```bash
docker compose exec frontend pnpm add pinia-plugin-persistedstate
```

#### CartStore.mjs - Frissített verzió perzisztenciával

**`CartStore.mjs`:**

```javascript
import { defineStore } from "pinia";
import { ref } from "vue";

export const useCart = defineStore("cart", () => {
    const productsInCart = ref([]);
    
    function addToCart(product){
        productsInCart.value.push(product);
    }

    return{
        productsInCart,
        addToCart,
    };
}, {
    persist: true,  // 🔑 Ez biztosítja az adatok megőrzését!
});
```

---

## 📚 Új Anyag: Settings Store

**Store beállítások tárolására**

Használható:
- 🌍 Nyelv váltás
- 🌙 Dark mode
- 🎨 Témaváltás
- 💱 Pénznem mentése webshopban
- És egyéb felhasználói beállítások...

---

## 🎯 Összefoglalás

Ez a projekt bemutatja:
- ✅ Pinia store használatát (Products, Cart, Settings)
- ✅ ShadCN Vue komponensek integrálását
- ✅ Dinamikus adatok megjelenítését
- ✅ Kosár funkció implementálását
- ✅ Adatok perzisztenciáját (localStorage)
- ✅ Feltételes CSS osztályokat

**Jó gyakorlást! 🚀**