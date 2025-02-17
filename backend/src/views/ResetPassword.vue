<script setup>

import GuestLayout from "../components/GuestLayout.vue";
</script>

<template>

    <GuestLayout title="Type your new password">
        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                <div >
                    <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm/6 font-medium text-gray-600">New Password</label>
                    </div>
                    <div class="mt-2">
                        <input type="password" name="password" id="password"  required="" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-600
 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-pink-500 sm:text-sm/6" />
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password-repeat" class="block text-sm/6 font-medium text-gray-600">New Password repeat</label>
                        <div class="text-sm">
                        </div>
                    </div>
                    <div class="mt-2">
                        <input type="password" name="password-repeat" id="password-repeat"  required="" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-600
 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-pink-500 sm:text-sm/6" />
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                    <router-link :to="{name : 'login'}" class="font-semibold text-pink-300 hover:text-pink-500" >Go back to Login</router-link>
                    </div>
                    <button type="submit" class="flex w-full justify-center rounded-md bg-pink-300 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-sm hover:bg-pink-400 ">Sign in</button>

                </div>


        </div>
    </GuestLayout>
</template>


<style scoped>

</style>
