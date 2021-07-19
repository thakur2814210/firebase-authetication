
    const firebaseConfig = {
      apiKey: "AIzaSyAjg2lIlpNGOR2AuobziEHkyH8ITIbYntE",
      authDomain: "cardition-585e0.firebaseapp.com",
      projectId: "cardition-585e0",
      storageBucket: "cardition-585e0.appspot.com",
      messagingSenderId: "529031989071",
      appId: "1:529031989071:web:3cc82269ee7b3c436b273a",
      measurementId: "G-51NVLWEC3P"
    };
    
    firebase.initializeApp(firebaseConfig);
    // const txtname=document.getElementById('txtname');
    const txtemail=document.getElementById('txtemail');
    const txtpassword=document.getElementById('txtpassword');
    
    const emaillog=document.getElementById('emaillog');
    const passlog=document.getElementById('passlog');
    
    const btnlogin=document.getElementById('btnlogin');
    const btnsignup=document.getElementById('btnsignup');
    const btnlogout=document.getElementById('btnlogout');
   
    // Initialize Firebase

    
    btnlogin.addEventListener('click', e =>{
    console.log("hello");
    const email1=emaillog.value;
    const pass1=passlog.value;
    
    const auth = firebase.auth();
    
    const promise=auth.signInWithEmailAndPassword(email1,pass1);
    promise.catch(e =>console.log(e.message));
    
    
    });

    btnsignup.addEventListener('click', e =>{
        // const name=txtname.value;
        const email=txtemail.value;
    const pass=txtpassword.value;
    console.log(email);
    console.log(pass);
    
    const auth = firebase.auth();
    
    const promise=auth.createUserWithEmailAndPassword(email,pass);
    promise
    .then(console.log('user logged in'))
    .catch(e =>console.log(e.message));
    
    });
    btnlogout.addEventListener('click', e =>{
      firebase.auth().signOut();
      console.log("user log out")
    
    });


    firebase.auth().onAuthStateChanged(firebaseUser =>{
      if(firebaseUser){
        console.log(firebaseUser);
      }
      else{
        
        console.log('not logged in');
      }
    })
    
  