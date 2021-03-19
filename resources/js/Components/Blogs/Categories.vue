<template>
  <section>

      
      <div class="cateories" v-for="item in res" :key="item.id">

          <div class="inner-content">

              <div class="post">

                  <div class="title">{{item.title}}</div>

                  <div class="description">{{item.description}}</div>

                  <div class="link"><a :href="'/a/'+item.slug">Læs mere</a></div>

              </div>

          </div>


      </div>


  </section>
</template>

<script>

export default {
  props: ["res"],
  computed: {
    


  },
  data() {
      return {
          message:'Hello World'
      }
  },
  mounted(){

    console.log(this.res)

  }
};
</script>