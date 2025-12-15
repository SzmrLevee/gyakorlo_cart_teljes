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
