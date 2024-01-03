<template>
    <div v-if="Head.ShowHead" class="row mt-1">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <nav class="navbar navbar-breadcrumb navbar-expand-md bg-breadcrumb breadcrumb-borde">
                    <div class="mr-auto">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb clearfix pt-3">
                                <li style="cursor:pointer;" v-if="oHead.isreturn" @click="Regresar" class="breadcrumb-item active">
                                    Volver
                                </li>
                                <li class="breadcrumb-item active">{{RegresaTitulo}}</li>
                            </ol>
                        </nav>
                    </div>
                    <form class="form-inline">
                        <slot name="component"></slot>
                        <template v-if="oHead.BtnNewShow">
                            <button  v-if="oHead.isModal" @click="Nuevo(0)"  data-toggle="modal" data-target="#ModalForm"  data-backdrop="static" data-keyboard="false"  type="button" class="btn btn-pink mb-2 mr-1">
								<i class="far fa-plus-circle"></i>
								{{oHead.BtnNewName}}
							</button>
                            <button v-else type="button" @click="Nuevo(1)"  class="btn btn-pink mb-2 mr-1">
								<i class="far fa-plus-circle"></i>
								{{oHead.BtnNewName}}
							</button>
                        </template>
                    </form>
                </nav>
            </div>
        </div>
</template>

<script>
export default {
    props:['oHead'],
    data() {
        return {
            Head:{
                ShowHead:true,
                Title:'Datos',
                BtnNewShow:false,
                BtnNewName:'Nuevo',
                isreturn:true,
                isModal:false,
                isEmit:false,
                Url:'',
                ObjReturn:'',
                NameReturn:'Regresar',
             }

        }
    },methods: {
         Nuevo()
        {

            if (this.Head.isModal==true)
            {
                this.bus.$emit('Nuevo',true);
            }
            else{
                this.bus.$emit('Nuevo');
            }
        },
        Regresar()
        {

            if(this.Head.isEmit){
                //this.bus.$emit('Regresar');
                this.bus.$emit(this.Head.NameReturn);
            }else{
                this.$router.push({name:this.Head.Url, params:{obj:this.Head.ObjReturn}})
            }
        }
    },computed: {
        RegresaTitulo(){

             if(this.oHead.Title!=undefined){ this.Head.Title=this.oHead.Title;}
             if(this.oHead.BtnNewShow!=undefined){ this.Head.BtnNewShow=this.oHead.BtnNewShow;}
             if(this.oHead.BtnNewName!=undefined){ this.Head.BtnNewName=this.oHead.BtnNewName;}
             if(this.oHead.isreturn!=undefined){ this.Head.isreturn=this.oHead.isreturn;}
             if(this.oHead.isModal!=undefined){ this.Head.isModal=this.oHead.isModal;}
            if(this.oHead.isEmit!=undefined){ this.Head.isEmit=this.oHead.isEmit;}
            if(this.oHead.Url!=undefined){ this.Head.Url=this.oHead.Url;}
            if(this.oHead.ObjReturn!=undefined){ this.Head.ObjReturn=this.oHead.ObjReturn;}
            if(this.oHead.ShowHead!=undefined){ this.Head.ShowHead=this.oHead.ShowHead;}
            if(this.oHead.NameReturn!=undefined){ this.Head.NameReturn=this.oHead.NameReturn;}

            return this.Head.Title;
        }
    },

}
</script>

<style>

</style>
