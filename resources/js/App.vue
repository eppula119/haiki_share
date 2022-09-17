<template>
  <div>
    <Header />
    <!-- <Message /> -->
    <RouterView />
  </div>
</template>

<script>
// ヘッダーコンポーネント読み込み
import Header from "./components/Header.vue";

export default {
  components: {
    Header
  },
  computed: {
  },
  methods: {},
};
</script>