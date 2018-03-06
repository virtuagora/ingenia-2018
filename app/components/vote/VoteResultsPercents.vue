<template>
  <nav class="level">
    <div class="level-item has-text-centered" v-for="(val,index) in vals" :key="index">
      <div>
        <p class="heading">{{val.option}}</p>
        <p class="title">{{val.percent}} %</p>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  props: ["options", "values"],
  data() {
    return {
      vals: [],
      percents: [],
      total: 0
    };
  },
  created: function() {
    this.total = this.values.reduce((accumulator, val) => accumulator + val);
    this.percents = this.convertToPercentages(this.values, this.total)
    this.vals = this.options.map((option, index) => {
      return {
        option: option,
        value: this.values[index],
        percent: this.percents[index]
      };
    });
  },
  methods: {
    // https://stackoverflow.com/questions/30160272/changing-numbers-to-percentage-from-an-array
    convertToPercentages: function(arr, max) {
      return arr.map(function(d, i) {
        // time for some math
        // we know the max is n
        // so, to produce a whole-number percent
        // we need to divide each digit `d` by `max`,
        // then multiply by 100
        // we then (`| 0`) to remove the decimals (same as Math.floor)
        return (100 * d / max) | 0;
      });
    }
  }
};
</script>

<style lang="scss" scoped>
.level {
  display: block;
}
.level-item:not(:last-child) {
  margin-bottom: 0.75rem;
}
</style>

