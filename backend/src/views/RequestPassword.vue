<script setup>

import GuestLayout from "../components/GuestLayout.vue";
</script>

<template>

<GuestLayout title="Request new password">
            <form class="space-y-6" action="#" method="POST">
                <div>
                    <div class="flex items-center justify-between">
                        <label for="email" class="block text-sm/6 font-medium text-gray-600
">Email</label>
                        <div class="text-sm">
                            <router-link :to="{name : 'login'}" class="font-semibold text-pink-300 hover:text-pink-500" >Remember password?</router-link>
                        </div>
                    </div>
                    <div class="mt-2">
                        <input type="email" name="email" id="email" autocomplete="email" required="" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-600
 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-pink-500 sm:text-sm/6" />
                    </div>
                </div>



                <div>
                    <button type="submit" class="flex w-full justify-center rounded-md bg-pink-300 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-sm hover:bg-pink-400 ">Submit</button>

                </div>
            </form>
</GuestLayout>
</template>


<style scoped>

</style>
