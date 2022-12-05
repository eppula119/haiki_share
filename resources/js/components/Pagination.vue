<template>
    <ul class="c-paginationWrap">
      <!-- 前のページ(左矢印)リンク) -->
      <li class="c-paginationWrap__number">
        <a
          class="c-paginationLink"
          :class="(currentPage == 1) ? 'is-disabled' : ''"
          @click="changePage(currentPage - 1)"><i class="fa-solid fa-less-than"></i></a>
      </li>
      <li class="c-paginationWrap__number" v-for="page in frontPageRange" :key="page">
        <a
          class="c-paginationLink"
          :class="(isCurrent(page)) ? 'is-active' : ''"
          @click="changePage(page)">{{ page }}
        </a>
      </li>
      <!-- 先頭側のドット表記 -->
      <li v-show="front_dot" class="c-paginationWrap__number">
        <span class="c-paginationLink is-disabled">...</span>
      </li>
      <li class="c-paginationWrap__number" v-for="page in middlePageRange" :key="page">
        <a
        class="c-paginationLink"
        :class="(isCurrent(page)) ? 'is-active' : ''"
        @click="changePage(page)">{{ page }}</a>
      </li>
      <!-- 最後尾側のドット表記 -->
      <li v-show="end_dot" class="c-paginationWrap__number">
        <span class="c-paginationLink is-disabled">...</span>
      </li>
      <li class="c-paginationWrap__number" v-for="page in endPageRange" :key="page">
        <a
          class="c-paginationLink"
          :class="(isCurrent(page)) ? 'is-active' : ''"
          @click="changePage(page)">{{ page }}</a>
      </li>
      <!-- 次のページ(右矢印)リンク) -->
      <li class="c-paginationWrap__number">
        <a
          class="c-paginationLink"
          :class="(currentPage >= lastPage) ? 'is-disabled' : ''"
          @click="changePage(currentPage + 1)"><i class="fa-solid fa-greater-than"></i></a>
      </li>
    </ul>
</template>

<script>

export default {
  props: {
    currentPage: { // 現在のページ番号
      type: Number,
      default: 1,
      required: true,
    },
    lastPage: { // 最後のページ番号
      default: "",
      required: true,
    },
  },
  data() {
    return {
      range: 3, // 中間の連続してページ番号を表示させる個数(例： 1,2,...,5,6,7,8,9,...,13,14 などの5~9の部分)
      front_dot: false, // ページネーションの中間より左側のドット「...」部分
      end_dot: false, // ページネーションの中間より右側のドット「...」部分
    }
  },
  computed: {
    // ページネーションの1ページと2ページのページ番号は表示させる
    frontPageRange() {
      // ページ数がrange + 4以下の場合
      if (!this.sizeCheck) {
        // 先頭側のドット表記を表示しない
        this.front_dot = false
        // 最後尾側のドット表記を表示しない
        this.end_dot = false
        return this.calRange(1, this.lastPage)
      }
      // ページ数がrange + 4以上の場合、先頭側は「1」と「2」ページを表示させる
      return this.calRange(1, 2)
    },
    // ページネーションの表示させる連続したページ番号の中間部分
    middlePageRange() {
      // 中間部分の表示させる最初のページ番号
      let start = ""
      // 中間部分の表示させる最後のページ番号
      let end = ""
      // ページ数がrange + 4以下の場合は中間部分は表示させない
      if (!this.sizeCheck) return []
      // 現在のページがrangeより小さい場合
      if (this.currentPage <= this.range) {
        // 中間部分は「3」「4」「5」ページを表示させる
        start = 3
        end = this.range + 2
        // 先頭側のドット表記を表示しない
        this.front_dot = false
        // 最後尾側のドット表記を表示させる
        this.end_dot = true
      // 現在のページがrangeより大きく、「最後のページ - range」より大きい場合
      } else if (this.currentPage > this.lastPage - this.range) {
        // 中間部分は「最後のページ - 2」のページのみを表示させる
        start = this.lastPage - this.range - 1
        end = this.lastPage - 2
        // 先頭側のドット表記を表示させる
        this.front_dot = true
        // 最後尾側のドット表記を表示しない
        this.end_dot = false
      // 現在のページがrangeより大きく、「最後のページ - range」より小さい場合
      } else {
        // 中間部分は「現在のページ - 1」「現在のページ」「現在のページ + 1」のページを表示させる
        start = this.currentPage - Math.floor(this.range / 2)
        end = this.currentPage + Math.floor(this.range / 2)
        // 先頭側のドット表記を表示させる
        this.front_dot = true
        // 最後尾側のドット表記を表示させる
        this.end_dot = true
      }
      return this.calRange(start, end)
    },
    // ページネーションの最後のページと最後から2番目のページ番号は表示させる
    endPageRange() {
      if (!this.sizeCheck) return []
      return this.calRange(this.lastPage - 1, this.lastPage)
    },
    // データ数が少なく、ページ数がrange + 4で収まる場合の処理
    sizeCheck() {
      // ページ数がrange + 4で収まる場合(「4」は先頭2つ、最後2つのページを足した数)
      if (this.lastPage <= this.range + 4) {
        return
      }
      return
    },
  },
  methods: {
    // ページネーションの表示させるページ番号の個数を作成
    calRange(start, end) {
      const range = []
      for (let i = start; i <= end; i++) {
        range.push(i)
      }
      return range
    },
    // ページ切り替え
    changePage(page) {
      this.$emit("changePage", page)
    },
    // 現在のページがどうか判別
    isCurrent(page) {
      return page === this.currentPage
    },
  }
}
</script>