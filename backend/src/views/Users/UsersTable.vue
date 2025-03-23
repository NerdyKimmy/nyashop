<template>
    <div class="bg-white p-4 rounded-lg shadow animate-fade-in-down">
        <div class="flex justify-between border-b-2 pb-3">
            <div class="flex items-center">
                <span class="whitespace-nowrap mr-3">Per Page</span>
                <select @change="getUsers(null)" v-model="perPage"
                        class="appearance-none relative block w-24 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-pink-300 focus:border-pink-300 focus:z-10 sm:text-sm">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="ml-3">Found {{ users.total }} users</span>
            </div>
            <div>
                <input v-model="search" @change="getUsers(null)"
                       class="appearance-none relative block w-48 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-pink-300 focus:border-pink-300 focus:z-10 sm:text-sm"
                       placeholder="Type to Search users">
            </div>
        </div>

        <table class="table-auto w-full">
            <thead>
            <tr>
                <TableHeaderCell field="id" :sort-field="sortField" :sort-direction="sortDirection"
                                 @click="sortUsers('id')">
                    ID
                </TableHeaderCell>
                <TableHeaderCell field="name" :sort-field="sortField" :sort-direction="sortDirection"
                                 @click="sortUsers('name')">
                    Name
                </TableHeaderCell>
                <TableHeaderCell field="email" :sort-field="sortField" :sort-direction="sortDirection"
                                 @click="sortUsers('email')">
                    Email
                </TableHeaderCell>
                <TableHeaderCell field="created_at" :sort-field="sortField" :sort-direction="sortDirection"
                                 @click="sortUsers('created_at')">
                    Create Date
                </TableHeaderCell>
                <TableHeaderCell field="actions">
                    Actions
                </TableHeaderCell>
            </tr>
            </thead>
            <tbody v-if="users.loading || !users.data.length">
            <tr>
                <td colspan="6">
                    <Spinner v-if="users.loading" />
                    <p v-else class="text-center py-8 text-gray-700">
                        There are no users
                    </p>
                </td>
            </tr>
            </tbody>
            <tbody v-else>
            <tr v-for="(user, index) in users.data" :key="user.id">
                <td class="border-b p-2">{{ user.id }}</td>
                <td class="border-b p-2">{{ user.name }}</td>
                <td class="border-b p-2 max-w-[200px] whitespace-nowrap overflow-hidden text-ellipsis">
                    {{ user.email }}
                </td>
                <td class="border-b p-2">{{ user.created_at }}</td>
                <td class="border-b p-2">
                    <Menu as="div" class="relative inline-block text-left">
                        <div>
                            <template v-if="user.id !== 1">
                            <MenuButton
                                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-black bg-opacity-0 text-sm font-medium text-white hover:bg-opacity-5 focus:bg-opacity-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75"
                            >
                                <DotsVerticalIcon class="h-5 w-5 text-pink-300" aria-hidden="true" />
                            </MenuButton>
                            </template>
                        </div>
                        <transition
                            enter-active-class="transition duration-100 ease-out"
                            enter-from-class="transform scale-95 opacity-0"
                            enter-to-class="transform scale-100 opacity-100"
                            leave-active-class="transition duration-75 ease-in"
                            leave-from-class="transform scale-100 opacity-100"
                            leave-to-class="transform scale-95 opacity-0"
                        >
                            <MenuItems
                                class="absolute z-10 right-0 mt-2 w-32 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                            >
                                <div class="px-1 py-1">
                                    <template v-if="user.id !== 1">
                                        <MenuItem v-slot="{ active }">
                                            <button
                                                :class="[
                                                        active ? 'bg-pink-400 text-white' : 'text-gray-900',
                                                        'group flex w-full items-center rounded-md px-2 py-2 text-sm'
                                                    ]"
                                                @click="editUser(user)"
                                            >
                                                <PencilIcon :active="active" class="mr-2 h-5 w-5 text-pink-300" aria-hidden="true" />
                                                Edit
                                            </button>
                                        </MenuItem>
                                    </template>
                                    <MenuItem v-slot="{ active }">
                                        <button
                                            :class="[
                                                    active ? 'bg-pink-400 text-white' : 'text-gray-900',
                                                    'group flex w-full items-center rounded-md px-2 py-2 text-sm'
                                                ]"
                                            @click="deleteUser(user)"
                                        >
                                            <TrashIcon :active="active" class="mr-2 h-5 w-5 text-pink-300" aria-hidden="true" />
                                            Delete
                                        </button>
                                    </MenuItem>
                                </div>
                            </MenuItems>
                        </transition>
                    </Menu>
                </td>
            </tr>
            </tbody>
        </table>

        <div v-if="!users.loading" class="flex justify-between items-center mt-5">
            <div v-if="users.data.length">
                Showing from {{ users.from }} to {{ users.to }}
            </div>
            <nav
                v-if="users.total > users.limit"
                class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px"
                aria-label="Pagination"
            >
                <a
                    v-for="(link, i) in users.links"
                    :key="i"
                    :disabled="!link.url"
                    href="#"
                    @click="getForPage($event, link)"
                    aria-current="page"
                    class="relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap"
                    :class="[
                        link.active ? 'z-10 bg-pink-50 border-pink-300 text-pink-400' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                        i === 0 ? 'rounded-l-md' : '',
                        i === users.links.length - 1 ? 'rounded-r-md' : '',
                        !link.url ? 'bg-gray-100 text-gray-700' : ''
                    ]"
                    v-html="link.label"
                ></a>
            </nav>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import store from '../../store';
import Spinner from '../../components/core/Spinner.vue';
import { USERS_PER_PAGE } from '../../constants';
import TableHeaderCell from '../../components/core/Table/TableHeaderCell.vue';
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { DotsVerticalIcon, PencilIcon, TrashIcon } from '@heroicons/vue/outline';
import UserModal from './UserModal.vue';

const perPage = ref(USERS_PER_PAGE);
const search = ref('');
const users = computed(() => store.state.users);
const sortField = ref('updated_at');
const sortDirection = ref('desc');

const user = ref({});
const showUserModal = ref(false);

const emit = defineEmits(['clickEdit']);

onMounted(() => {
    getUsers();
});

function getForPage(ev, link) {
    ev.preventDefault();
    if (!link.url || link.active) {
        return;
    }
    getUsers(link.url);
}

function getUsers(url = null) {
    store.dispatch('getUsers', {
        url,
        search: search.value,
        per_page: perPage.value,
        sort_field: sortField.value,
        sort_direction: sortDirection.value
    });
}

function sortUsers(field) {
    if (field === sortField.value) {
        sortDirection.value = sortDirection.value === 'desc' ? 'asc' : 'desc';
    } else {
        sortField.value = field;
        sortDirection.value = 'asc';
    }
    getUsers();
}

function showAddNewModal() {
    showUserModal.value = true;
}

function deleteUser(user) {
    if (!confirm('Are you sure you want to delete the user?')) {
        return;
    }
    store.dispatch('deleteUser', user.id).then(() => {
        store.dispatch('getUsers');
    });
}

function editUser(user) {
    emit('clickEdit', user);
}
</script>

<style scoped>
/* Your styles here */
</style>
