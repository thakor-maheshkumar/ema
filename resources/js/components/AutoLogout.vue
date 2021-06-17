<template>
    <div v-if="warningZone">
        <p>Are you still there?</p>
    </div>
</template>

<script>
    export default {
        name: "AutoLogout",
        data(){
            return {
                events: ['click','mousemove','mousedown','scroll','keypress','load'],
                warningTimer: null,
                logoutTime: null,
                warningZone:false,
            }
        },
        mounted() {
            console.log('Component mounted.')
            this.events.forEach(function(event){
                window.addEventListener(event,this.resetTimer)
            },this);

            this.setTimers()
        },
        destroyed() {
            this.events.forEach(function(event){
                window.removeEventListener(event,this.resetTimer)
            },this);

            this.resetTimer()
        },
        methods:{
            setTimers(){
                // this.warningTimer = setTimeout(this.warningMessage,13*60*1000)
                this.logoutTimer = setTimeout(this.logoutUser,global.login_activity*60*1000)

                // this.warningZone = false
            },
            warningMessage(){
                // this.warningZone = true
            },
            logoutUser(){
                document.getElementById('logout-form').submit();
            },
            resetTimer(){
                // clearTimeout(this.warningTimer)
                clearTimeout(this.logoutTimer)

                this.setTimers()
            }
        }
    }
</script>
