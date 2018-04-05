<template>
  <section class="section">
    <div class="container">
      <div class="columns">
        <div class="column">
          <div class="content">
            <h3>
              <b>Descripción del proyecto</b>
            </h3>
            <p>{{project.abstract}}</p>
          </div>
        </div>
        <div class="column">
          <div class="content">
            <h3>
              <b>Fundamentación del proyecto</b>
            </h3>
            <p>{{project.foundation}}</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  props:['project']
}
</script>
