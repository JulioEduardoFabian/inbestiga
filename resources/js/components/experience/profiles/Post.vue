<template>
    <div class="card mt-2 bg-success">
        <div class="card-body text-white">
            <p class="fw-bold">
                {{ post.postable ? post.postable.name : "Desconocido" }}
            </p>
            <p class="fw-semibold">{{ post.title }}</p>
            <p>{{ post.body }}</p>
            <p>{{ formatDate(post.created_at) }}</p>
            <button
                v-if="post.files[0]"
                class="btn btn-warning"
                @click="downloadFile(post.files[0].url)"
            >
                Descargar <i class="bx bxs-download"></i>
            </button>
            <button
                v-if="store.authUser.roles[0]"
                class="btn btn-icon btn-danger ms-2"
                @click="deletePost(post)"
            >
                <i class="bx bx-trash"></i>
            </button>
            <button
                v-if="store.authUser.roles[0].name == 'Experience'"
                @click="enableDownload(post)"
                class="btn btn-icon btn-primary ms-1"
            >
                <i class="bx bx-check"></i>
            </button>
        </div>
    </div>
</template>
<script>
import moment from "moment";
import { userStore } from "../../../stores/UserStore";

export default {
    setup() {
        const store = userStore();
        return {
            store,
        };
    },
    props: {
        post: Object,
    },
    methods: {
        formatDate(date) {
            return moment(date).format("DD/MM/YYYY HH:mm:ss a");
        },
        downloadFile(url) {
            window.open("https://inbestiga.com/inbestiga/public/files/" + url);
        },
        enableDownload(post) {
            axios
                .get("/api/enable-file/" + post.files[0].id)
                .then((result) => {
                    post.files[0].type = 2;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        deletePost(post) {
            if (confirm("Tienes la seguridad de eliminar este post?")) {
                axios
                    .delete("/api/post/" + post.id)
                    .then((result) => {
                        this.$emit("getQuotation");
                    })
                    .catch((err) => {
                        console.log(err);
                    });
            }
        },
    },
};
</script>
<style lang=""></style>
