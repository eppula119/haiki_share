<template>
  <div class="c-message" v-show="message">
    {{ message }}
  </div>
</template>

<script>
  import { mapState } from 'vuex'

  export default {
    computed: {
      //メッセージストアのステートにセットされてある「メッセージ内容」を返す
      ...mapState({
        message: state => state.message.content
      })
    }
  }
</script>