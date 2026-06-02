import { ref } from 'vue';
import axios from 'axios';

export function useSubitem() {
    const carregando = ref(false);

    async function index() {
        carregando.value = true;

        try {
            const res = await axios.get(route('subitem.index'));
            return res.data;
        } catch (error) {
            console.error("Axios erro:", err);
            return { sucesso: false, msg: err.response?.data?.message || "Erro desconhecido" };
        } finally { carregando.value = false }
    }
    async function store(item) {
        carregando.value = true;

        try {
            const res = await axios.post(route("subitem.store"), item)

            return res.data;
        } catch (error) {
            console.error("Axios erro:", err);
            return { sucesso: false, msg: err.response?.data?.message || "Erro desconhecido" };
        } finally { carregando.value = false }
    }

    return {
        carregando,
        index,
        store,
    };
}