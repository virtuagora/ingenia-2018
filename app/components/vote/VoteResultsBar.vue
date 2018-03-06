
<script>
import { Doughnut } from "vue-chartjs";
import pattern from "patternomaly";

const colors = [
  "#F44336",
  "#E91E63",
  "#9C27B0",
  "#673AB7",
  "#3F51B5",
  "#2196F3",
  "#03A9F4",
  "#00BCD4",
  "#009688",
  "#4CAF50",
  "#8BC34A",
  "#CDDC39",
  "#FFEB3B",
  "#FFC107",
  "#FF9800",
  "#FF5722",
  "#795548",
  "#9E9E9E",
  "#607D8B"
];

export default {
  extends: Doughnut,
  props: ["options", "values"],
  data() {
    return {};
  },
  mounted() {
    props: ["options", "values"];
    // Overwriting base render method with actual data.
    this.renderChart(
      {
        labels: this.options,
        datasets: [
          {
            label: "Users",
            backgroundColor: this.createPatterns(this.values.length),
            data: this.values
          }
        ]
      },
      {
        tooltips:{
          displayColors: false
        },
        legend: {
          // display: false,
          position: "bottom",
          labels: {
            // This more specific font property overrides the global property
          }
        },
        responsive: true,
        maintainAspectRatio: false
      }
    );
  },
  methods: {
    createPatterns: function(size) {
      let shuffled = colors.sort(function() {
        return 0.5 - Math.random();
      });
      return pattern.generate(shuffled.slice(0, size))
    }
  }
};
</script>

<style lang="scss" scope>

</style>
