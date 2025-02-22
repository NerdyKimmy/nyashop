<template>
    <div class="w-[200px]  transition-all bg-pink-300 text-white py-4 px-2">
        <router-link :to="{name: 'app.dashboard'}"
                     class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
      <span class="mr-2 text-gray-50">
        <HomeIcon class="w-5"/>
      </span>
            <span class="text-s">
        Dashboard
      </span>
        </router-link>
        <router-link :to="{name: 'app.products'}"
                     class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
      <span class="mr-2 text-gray-50">
        <ViewListIcon class="w-5"/>
      </span>
            <span class="text-s">
        Products
      </span>
        </router-link>
        <router-link :to="{name: 'app.dashboard'}"
                     class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
      <span class="mr-2 text-gray-50">
        <UsersIcon class="w-5"/>
      </span>
            <span class="text-s">
        Users
      </span>
        </router-link>
        <router-link :to="{name: 'app.dashboard'}"
                     class="flex items-center p-2 rounded transition-colors hover:bg-black/30">
      <span class="mr-2 text-gray-50">
        <ChartBarIcon class="w-5"/>
      </span>
            <span class="text-s">
        Reports
      </span>
        </router-link>
    </div>
</template>

<script setup>
import {HomeIcon, UsersIcon, ViewListIcon, ChartBarIcon} from '@heroicons/vue/outline'
</script>

<style scoped>

</style>
