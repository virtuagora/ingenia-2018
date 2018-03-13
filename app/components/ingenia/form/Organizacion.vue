<template>
  <div>
    <div class="field">
      <label class="label is-size-4">
        <i class="fas fa-angle-double-right"></i> ¿Las actividades las realizarán en coordinación con otras organizaciones y/o instituciones? * </label>
      <p>Se refiere a organizaciones y/o instituciones diferentes a la de pertenencia</p>
      <p>Si el proyecto se realiza en coordinación de alguna institución y/o organización deben adjuntar la carta aval.</p>
      <br>
      <b-field>
        <b-radio-button v-model="radioOrganizacion" :native-value="true" type="is-primary" size="is-medium">
          <span>
            <i class="fas fa-check"></i> Si</span>
        </b-radio-button>
        <b-radio-button v-model="radioOrganizacion" :native-value="false" type="is-primary" size="is-medium">
          <span>
            <i class="fas fa-times"></i> No</span>
        </b-radio-button>
      </b-field>
    </div>
    <p>Para descargar los modelos de Carta Aval y Carta de conformidad de los integrantes del grupo
      <a href="">
        <b>haz click aquí</b>
      </a>
    </p>
    <br>
    <div v-if="radioOrganizacion">
      <div class="field">
        <label class="label is-size-4">
          <i class="fas fa-angle-double-right"></i> Nombre de la organización *</label>
        <input type="text" class="input is-medium" placeholder="Requerido *">
      </div>
      <br>
      <div class="field">
        <label class="label is-size-4">
          <i class="fas fa-angle-double-right"></i> ¿Qué temas trabaja la organización?</label>
        <div class="columns">
          <div class="column">
            <div class="field">
              <b-checkbox v-model="tematicasOrganizacion" :native-value="1">Arte y Cultura</b-checkbox>
            </div>
            <div class="field">
              <b-checkbox v-model="tematicasOrganizacion" :native-value="2">Comunicación</b-checkbox>
            </div>
            <div class="field">
              <b-checkbox v-model="tematicasOrganizacion" :native-value="3">Deporte y recreación</b-checkbox>
            </div>
          </div>
          <div class="column">
            <div class="field">
              <b-checkbox v-model="tematicasOrganizacion" :native-value="4">Diversidad Sexual</b-checkbox>
            </div>
            <div class="field">
              <b-checkbox v-model="tematicasOrganizacion" :native-value="5">Integración Social</b-checkbox>
            </div>
            <div class="field">
              <b-checkbox v-model="tematicasOrganizacion" :native-value="6">Medio Ambiente</b-checkbox>
            </div>

          </div>
          <div class="column">
            <div class="field">
              <b-checkbox v-model="tematicasOrganizacion" :native-value="7">Salud y Discapacidad</b-checkbox>
            </div>
            <div class="field">
              <b-checkbox v-model="tematicasOrganizacion" :native-value="8">Ciudadanía y Participación</b-checkbox>
            </div>
            <div class="field">
              <input type="text" class="input" placeholder="Otro tema">
            </div>
          </div>
        </div>
      </div>
      <div class="field">
        <label class="label is-size-4">
          <i class="fas fa-angle-double-right"></i> Localidad *</label>
        <p>¿Donde desarrolla sus actividades la organización?</p>
        <br>
        <b-field grouped>
          <b-field label="Nodo" expanded>
            <b-select placeholder="Nodo" expanded>
              <option>Mr.</option>
              <option>Ms.</option>
            </b-select>
          </b-field>
          <b-field label="Departamento" expanded>
            <b-select placeholder="Departamento" expanded>
              <option>Mr.</option>
              <option>Ms.</option>
            </b-select>
          </b-field>
          <b-field label="Localidad" expanded>
            <b-select placeholder="Localidad" expanded>
              <option>Mr.</option>
              <option>Ms.</option>
            </b-select>
          </b-field>
        </b-field>
      </div>
      <div class="field">
        <label class="label is-size-4">
          <i class="fas fa-angle-double-right"></i> Carta de Aval *</label>
        <p>Para descargar los modelos de Carta Aval, haga <a href="#" target="_blank">clic aquí</a></p>
        <br>
        <b-field>
          <b-upload v-model="file">
            <a class="button is-primary">
              <b-icon icon="upload"></b-icon>
              <span>Explorar</span>
            </a>
          </b-upload>
          <div v-if="file && file.length">
            <span class="file-name">
              {{ file[0].name }}
            </span>
          </div>
          <div v-else>
 <span class="file-name">
              Haga clic para subir
            </span>
          </div>
        </b-field>
      </div>
    <br>
      
      <div class="field">
        <label class="label is-size-4">
          <i class="fas fa-angle-double-right"></i> Redes y contacto *</label>
        <div class="field is-grouped">
          <div class="control">
            <a @click.prevent class="button is-medium is-static">
              <i class="fas fa-globe"></i>
            </a>
          </div>
          <p class="control is-expanded">
            <input class="input is-medium" type="text" placeholder="URL página web (Ej: http://www.organizacion.org)">
          </p>
        </div>
        <div class="field is-grouped">
          <div class="control">
            <a @click.prevent class="button is-medium is-static">
              <i class="fab fa-facebook"></i>
            </a>
          </div>
          <p class="control is-expanded">
            <input class="input is-medium" type="text" placeholder="URL página de Facebook">
          </p>
        </div>
        <div class="field is-grouped">
          <div class="control">
            <a @click.prevent class="button is-medium is-static">
              <i class="fas fa-envelope"></i>
            </a>
          </div>
          <p class="control is-expanded">
            <input class="input is-medium" type="email" placeholder="Email de contacto">
          </p>
        </div>
        <div class="field is-grouped">
          <div class="control">
            <a @click.prevent class="button is-medium is-static">
              <i class="fas fa-phone"></i>
            </a>
          </div>
          <p class="control is-expanded">
            <input class="input is-medium" type="text" placeholder="Ej: 0342 - 4123456">
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      radioOrganizacion: true,
      tematicasOrganizacion: [],
      file: []
    };
  }
};
</script>
