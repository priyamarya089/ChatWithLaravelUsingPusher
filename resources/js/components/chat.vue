<template>
   <div class="row">

        <div class="col-2">
            <div class="card card-default">
                <div class="card-header">Active Users</div>
                <div class="card-body">
                    <ul>
                        <li class="py-2" v-for="(user, index) in users" :key="index" >
                            {{ user.name }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-2">
            <div class="card card-default">
                <div class="card-header">Users</div>
                <div class="card-body">
                    <ul>
                        <li class="py-2">
                           <a href="#messagebox" id="to" @click="fetchMessages(1)"  >priyam</a>
                        </li>
                        <li class="py-2" >
                           <a href="#messagebox" id="to" @click="fetchMessages(2)"  >kamal</a>
                        </li>
                        <li class="py-2">
                           <a href="#messagebox" id="to" @click="fetchMessages(3)"  >temp</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

       <div class="col-8" id="messagebox">
           <div class="card card-default">
               <div class="card-header">Messages</div>
               <div class="card-body p-0">
                   <ul class="list-unstyled" style="height:300px; overflow-y:scroll" v-chat-scroll>
                       <li class="p-2" v-for="(message, index) in messages" :key="index" >
                           <strong>{{ message.user.name }}</strong> : 
                           {{ message.message }}
                       </li>
                   </ul>
               </div>

               <input
                    @keyup="sendTypingEvent"
                    @keyup.enter="sendMessage"
                    v-model="newMessage"
                    type="text"
                    name="message"
                    id="message"
                    placeholder="Enter your message..."
                    class="form-control">
           </div>
           <span class="text-muted" v-if="activeUser" >{{ activeUser.name }} is typing {{ typingmessage }}</span>
       </div>
   </div>
</template>

<script>
    var to ;
    export default {
        props:['user'],
        mounted(){
        },   
        data() {
            return {
                messages: [],
                newMessage: '',
                users:[],
                activeUser: false,
                typingmessage: false,
                typingTimer: false,
            }
        },
        created() {
            this.fetchMessages(1);
            // console.log(this.user);
            var channel = window.Echo.join('room');
            Echo.join('room')
                .here(user => {
                    this.users = user;
                })
                .joining(user => {
                    this.users.push(user);
                })
                .leaving(user => {
                    this.users = this.users.filter(u => u.id != user.id);
                })
                .listen('.my-event', (event) => {
                    this.messages.push(event.message);
                })
                .listenForWhisper('typing', ( { type , user } ) => {
                   this.activeUser = user;
                   this.typingmessage = type;
                   
                    if(this.typingTimer) {
                        clearTimeout(this.typingTimer);
                    }

                   this.typingTimer = setTimeout(() => {
                       this.activeUser = false;
                   }, 1000);
                })
        },

        methods: {
            fetchMessages(x) {
                this.to=x;
                axios.get('/messages/'+x).then(response => {
                    this.messages = response.data;
                })
            },

            sendMessage() {
                this.messages.push({
                    user: this.user,
                    message: this.newMessage
                });

                axios.post('/messages', {message: this.newMessage , to : this.to});
               
                this.newMessage = '';
            },

            sendTypingEvent() {
                var typingmessage = document.getElementById('message').value;
                Echo.join('room')
                    .whisper('typing', { type : typingmessage , user : this.user }  );
            }
        }
    }
</script>
