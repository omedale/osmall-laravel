@include("common.head")
<style>
.cover {
	padding:0;
	height: 815px;
	background-size: cover;
	background-repeat: no-repeat;
	background-position: center center;
	background-image:url('/images/cprofile/risk_forest.jpg');
} 
.title {
	margin-top:50px;
	font-family:LatoLatin;
	padding-left:20px;
	padding-right:20px;
}
.pangolin {
	position:absolute;
	padding:0;
	margin:0;
	width: 300px;
	height: 400px;
	top: 10%;
	right: 5%;
 	padding:0;
	background-size: cover;
	background-repeat: no-repeat;
	background-position: center center;
	background-image:url('/images/cprofile/pangolin.jpg'); 
}
.text1 {
	position: absolute;
	top: 100px;
	left: 50px;
	background-color: white;
	width: 500px;
}
.text2 {
	position: absolute;
	left: 50px;
	background-color: white;
	width: 500px;
	top: 400px;
} 
.footer {
 	position: absolute;
	top: 800px; 
	left: 50px;
	background-color: white;
}
</style>
<body>
<div class="container" style="padding:0;width:980px !important">
<h1 class="title">3. Risk Management</h1>
<br>
<div class="cover">
<div class="pangolin"></div>
<p class="text1">
Network
Server access requires user IDs, passwords, and SSH public-key authentication. This limits access to the primary OpenSupermall servers to a predetermined group of authorized personnel who have their keys registered.
OpenSupermall mandatorily uses HTTPS on our website. It is a cryptographically enhanced protocol over which encrypted data is sent between users’ browser and OpenSupermall.com. HTTPS prevents attackers on the same network to intercept private information such as session variables, and log in as the victim.
System
OpenSupermall uses the Amazon Web Services (AWS) as a technical platform, as it is one of the most matured and secure cloud computing platform currently in the market. AWS provides all physical and basic infrastructural security including datacenter and disaster recovery.

Logistic Service Provider
Each request to the logistic service provider server contains an access token in the request headers. This access token is renewed each day, and is generated based on the OpenSupermall’s unique credentials. A security hash of the incoming API messages is always generated, which can be validated upon receipt of the messages. This is to prevent fraudulent messages or from unknown 3rd parties.
</p>
<p class="text2">
OpenCredit
OpenCredit is an internal payment mechanism at OpenSupermall.com, able to be used as a pseudo-currency. OpenSupermall therefore tracks OpenCredit creation and usage. Each OpenCredit usage is tagged by a security ID which is unique and helps in preventing malicious entries and forgeries.
When used for exchanging goods from OpenSupermall’s merchants, our internal security intelligence mechanism will deduct the payment from the available amount of OpenCredit points of the buyer. Each point is made sure to have a proper source which is valid, traceable and documented.

Application
All client and user access are via SSL/TLS which is a cryptographic protocol designed to protect against eavesdropping, tampering, and message forgery. OpenSupermall uses the Laravel platform which has an Object-relational mapping (ORM) which uses PDO parameter binding to avoid SQL injection. Parameter binding ensures that malicious users can’t pass in query data which could modify the query’s intent.
Sometimes a malicious third-party crafts a special link (or a form masquerading as a link) which when clicked initiates a request to another site where you are registered and happen to be authenticated into (by way of a session cookie). CSRF (cross-site request forgery) tokens are used to ensure that third-parties cannot initiate such a request. This is done by generating a token that must be passed along with the form contents. 
OpenSupermall encrypt passwords and sensitive informations using the bcrypt encryption method. Bcrypt has the best kind of repute that can be achieved for a cryptographic algorithm: it has been around for quite some time, used quite widely, "attracted attention", and yet remains unbroken to date. All cookies are automatically signed and encrypted. This means that if they are tampered with, OpenSupermall’s servers will automatically discard them.
</p>

<p class="footer">
@include("cprofile.backnhome")
</p>
</div>
</div>
</body>
