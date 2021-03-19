<template>
    <section>

        <form method="post" @submit.prevent="submit">

            <div class="field">
            <label>Company</label>
            <input type="text" name="company" value="" v-model="input.company">
            </div>

            <div class="field">
            <label>Cvr</label>
            <input type="text" name="vat" value="" v-model="input.vat">
            </div>

            <div class="field">
            <label>Address</label>
            <input type="text" name="address" value="" v-model="input.address">
            </div>

            <div class="field">
            <label>Zipcode</label>
            <input type="text" name="zipcode" value="" v-model="input.zipcode">
            </div>

            <div class="field">
            <label>City</label>
            <input type="text" name="city" value="" v-model="input.city">
            </div>

            <div class="field">
            <label>Telefon</label>
            <input type="text" name="city" value="" v-model="input.phone">
            </div>

            <div class="field">
            <label>Email</label>
            <input type="text" name="city" value="" v-model="input.email">
            </div>

            <button>send</button>

        </form>

    </section>
</template>

<script>


export default ({
    props: ["action"],
    data(){

        return { 
            input: {
                company: '',
                vat: '',
                address: '',
                zipcode: '',
                city: '',
                phone: '',
                email: ''
            }
        }
               
    },
    methods:{
        submit(){

            $.post( "/post/contact", this.input).done(function( data ) {

                alert( "Data Loaded: " + data );

            });
            

        }
    }

    
})
</script>
