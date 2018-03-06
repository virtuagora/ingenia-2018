  <template>
  <div class="columns">
    <div class="column is-3">
      <h4 class="title is-5">Step 1. Select your content</h4>
      <aside class="menu">
        <p class="menu-label">
          What content do you want to create?
        </p>
        <ul class="menu-list">
          <li>
            <router-link :to="{ name: 'createVote'}" exact-active-class="is-active" exact>A vote</router-link>
          </li>
        </ul>
      </aside>
    </div>
    <div class="column is-6 is-offset-1" v-show="typeSelected">
      <h4 class="title is-5">Step 2. Complete the fields</h4>
      <div class="field">
        <label class="label">Application Name</label>
        <p>Write the name of your application/web page.</p>
        <p>
          <b>Note:</b> This will be taken by search engines and social media to index your content into their search directories.
          <b>We strongly recommend you to not</b> change it too oftenly!</p>
        <div class="control">
          <input class="input is-large is-800" type="text" placeholder="Your input here..">
        </div>
        <span class="help">
          <b>Tip:</b> Be short and clear with the name</span>
      </div>
      <div class="field">
        <label class="label">Brief description</label>
        <div class="control">
          <textarea class="textarea" type="text" placeholder="Your input here.."></textarea>
        </div>
      </div>
      <hr>
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
export default {
  props: ['typeSelected'],
  data() {
    return {
      
    };
  },
  beforeCreate: function() {}
};
</script>
