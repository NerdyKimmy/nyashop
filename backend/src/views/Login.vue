<script setup>

import GuestLayout from "../components/GuestLayout.vue";
import {ref} from "vue";
import store from "../store";
import router from "../router";

let loading = ref(false);
let errorMsg = ref("");

const user = {
    email: '',
    password: '',
    remember: true
}

function login() {
    loading.value = true;
    store.dispatch('login', user)
        .then(() => {
            loading.value = false;
            router.push({name: 'app.dashboard'})
        })
        .catch(({response}) => {
            loading.value = false;
            errorMsg.value = response.data.message;
        })
}

</script>

<template>
<GuestLayout title="Sign in to your account">
            <form class="space-y-6"  method="POST" @submit.prevent="login">
                <div v-if="errorMsg" class="flex items-center justify-between py-3 px-5 bg-red-400 text-white rounded">
                    {{ errorMsg }}

                </div>
                <div>
                    <label for="email" class="block text-sm/6 font-medium text-gray-600
">Email address</label>
                    <div class="mt-2">
                        <input type="email" name="email" id="email" autocomplete="email" required="" v-model="user.email" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-600
 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-pink-500 sm:text-sm/6" />
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm/6 font-medium text-gray-600
">Password</label>
                        <div class="text-sm">
                            <router-link :to="{name : 'requestPassword'}" class="font-semibold text-pink-300 hover:text-pink-500" >Forgot password?</router-link>
                        </div>
                    </div>
                    <div class="mt-2">
                        <input type="password" name="password" id="password" autocomplete="current-password" required="" v-model="user.password" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-600
 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-pink-500 sm:text-sm/6" />
                    </div>
                </div>

                <div>
                    <button type="submit" class="flex w-full justify-center rounded-md bg-pink-300 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-sm hover:bg-pink-400 ">Sign in</button>

                </div>
            </form>
</GuestLayout>
</template>


<style scoped>

</style>
