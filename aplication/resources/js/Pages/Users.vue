<template>
    <section class="bg-slate-50 h-dvh flex flex-col">
        <div class="container mx-auto my-4 flex flex-col gap-4">
            <Link
                :href="route('home')"
                class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded w-fit"
                >Exit</Link
            >

            <div class="table w-full ...">
                <div class="table-header-group ...">
                    <div class="table-row">
                        <div class="table-cell text-left ...">Id</div>
                        <div class="table-cell text-left ...">Nome</div>
                        <div class="table-cell text-left ...">E-mail</div>
                        <div class="table-cell text-left ...">Phone</div>
                    </div>
                </div>
                <div class="table-row-group">
                    <div class="table-row" v-for="(item, id) in user" :key="id">
                        <div class="table-cell ...">{{ item.id }}</div>
                        <div class="table-cell ...">{{ item.name }}</div>
                        <div class="table-cell ...">{{ item.email }}</div>
                        <div class="table-cell ... relative">
                            {{ item.phone.number }}
                            <div class="absolute">{{ item.phone.comment.comment_desc }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    user: { default: null }
})
</script>

<style scoped>
.relative:hover{
    background-color: #EAEAEA;
}
.relative:hover .absolute{
    display: block !important;
}
.absolute{
    display: none !important;
    width: max-content;
    background-color: #6E67FD;
    padding: 0.4rem 1rem;
    border-radius: 0.4rem 0.4rem 0.4rem 0;
    color: white;
    top: -150%;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
}
</style>