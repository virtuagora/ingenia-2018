<template>
  <div class="div">
    <div class="tabs is-centered">
      <ul>
        <li :class="{'is-active': showGraph}">
          <a @click="showGraph = true">Graphs</a>
        </li>
        <li>
          <a @click="showPercents = true">Percents</a>
        </li>
        <li>
          <a @click="showTable = true">Table</a>
        </li>
      </ul>
    </div>
    <results-bar :options="options" :values="values" v-if="showGraph" :height="300" width="100%"></results-bar>
    <results-percents :options="options" :values="values" v-if="showPercents"></results-percents>
  </div>
</template>

<script>
import ResultsBar from "./VoteResultsBar";
import ResultsPercents from "./VoteResultsPercents";

export default {
  components: {
    ResultsBar,
    ResultsPercents
  },
  props: ["options", "values"],
  data() {
    return {
      showGraph: true,
      showPercents: false,
      showTable: false
    };
  },
  watch: {
    // if showGraph is true, toggle the other tabs to false
    showGraph: function(val) {
      if (val) {
        this.showPercents = false;
        this.showTable = false;
      }
    },
    // same logic follows..
    showPercents: function(val) {
      if (val) {
        this.showGraph = false;
        this.showTable = false;
      }
    },
    showTable: function(val) {
      if (val) {
        this.showGraph = false;
        this.showPercents = false;
      }
    }
  }
};
</script>
