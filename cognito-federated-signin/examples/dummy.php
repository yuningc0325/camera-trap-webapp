<?php
require "login.php";
?>

<!--
 Amazon Cognito Auth SDK for JavaScript
 Copyright 2017 Amazon.com, Inc. or its affiliates. All Rights Reserved.
 Licensed under the Apache License, Version 2.0 (the "License").
 You may not use this file except in compliance with the License.
 A copy of the License is located at
		 http://aws.amazon.com/apache2.0/
 or in the "license" file accompanying this file.
 This file is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES
 OR CONDITIONS OF ANY KIND, either express or implied. See the
 License for the specific language governing permissions
 and limitations under the License.
-->

<!DOCTYPE html>
<html lang="en-US">
<head>
	<title>Cognito Auth JS SDK Sample</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="stylesheets/styleSheetStart.css">
	<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script src="dist/amazon-cognito-auth.min.js"></script>
	<script src="dist/aws-cognito-sdk.min.js"></script>
	<!-- To enable the advanced security feature -->
	<!-- <script src="https://amazon-cognito-assets.<region>.amazoncognito.com/amazon-cognito-advanced-security-data.min.js"></script> -->
	<!-- E.g. -->
	<!-- <script src="https://amazon-cognito-assets.us-east-1.amazoncognito.com/amazon-cognito-advanced-security-data.min.js"></script> -->
</head>

<body  onload="onLoad()">
	<ul>
	  <li><a href="https://aws.amazon.com/cognito/" target="_blank"
		   title="Go to AWS Cognito Console">Cognito Console</a></li>
	  <li><a href="http://docs.aws.amazon.com/cognito/latest/developerguide/what-is-amazon-cognito.html"
		   target="_blank" title="See Cognito developer docs">Docs</a></li>
	</ul>

	<h1>
		<a href="http://docs.aws.amazon.com/cognito/latest/developerguide/what-is-amazon-cognito.html" target="_blank">
			<img src="img/MobileServices_AmazonCognito.png" alt="Amazon Cognito" title="Amazon Cognito"
		   style="width:144px;height:144px;"></a><br>
		Amazon Cognito Auth Demo
	</h1>

	<div class="centeredText">
		<p id="introPara" title="About this demo">
	  This sample web page demonstrates how to use the Amazon Cognito Auth SDK for JavaScript.
	  This SDK simplifies adding sign-up, sign-in functionality in your apps.
	  With this SDK, you can use Cognito User Pools’ app integration and federation features,
	  with a customizable UI hosted by AWS to sign up and sign in users, and with built-in federation
	  for external identity providers via SAML.
	  To learn more
	  see our <a href="http://docs.aws.amazon.com/cognito/latest/developerguide/what-is-amazon-cognito.html">Developer Guide</a>.<br>
	  <br>
	  This sample will initialize a CognitoAuth object and initiate the sign up / sign in flow.
	  You will need to substitute your own Cognito User Pool configuration values to make it work.
	  Look at the source for this page and read the comments to learn more.
	  You can also view the README.md of this sample page and the README.md of the Cognito Auth JavaScript SDK.
		</p>
	</div>

  <div><br></div>
  <div>
	  <p id="statusNotAuth" title="Status">
		  Sign-In to Continue
	  </p>
	  <p id="statusAuth" title="Status">
		  You have Signed-In
	  </p>
  </div>

	<div class="tabsWell">
		<div id="startButtons">
			<div class="button">
				<a class="nav-tabs" id="signInButton" href="javascript:void(0)" title="Sign in">Sign In</a>
			</div>
		</div>
		<div class="tab-content">
			<div class="tab-pane" id="userdetails">
				<p class="text-icon" title="Minimize" id="tabIcon" onclick="toggleTab('usertab');">_</p>
				<br>
				<h2 id="usertabtitle">Tokens</h2>
				<div class="user-form" id="usertab">
					<pre id="idtoken"> ... </pre>
		  <pre id="acctoken"> ... </pre>
		  <pre id="reftoken"> ... </pre>
				</div>
			</div>
		</div>
	</div>
	<script>

	var userPoolId ='ap-northeast-1_JACElFd4C';

	// Operations when the web page is loaded.
	function onLoad() {
		var i, items, tabs;
		items = document.getElementsByClassName("tab-pane");
		for (i = 0; i < items.length; i++) {
			items[i].style.display = 'none';
		}
		document.getElementById("statusNotAuth").style.display = 'block';
		document.getElementById("statusAuth").style.display = 'none';
		// Initiatlize CognitoAuth object
		var auth = initCognitoSDK();
		document.getElementById("signInButton").addEventListener("click", function() {
			userButton(auth);
		});
		var curUrl = window.location.href;
		auth.parseCognitoWebResponse(curUrl);
	}

	// Operation when tab is closed.
	function closeTab(tabName) {
	  document.getElementById(tabName).style.display = 'none';
	}

	// Operation when tab is opened.
	function openTab(tabName) {
		document.getElementById(tabName).style.display = 'block';
	}

	// Operations about toggle tab.
	function toggleTab(tabName) {
		if (document.getElementById("usertab").style.display == 'none') {
			document.getElementById("usertab").style.display = 'block';
			document.getElementById("tabIcon").innerHTML = '_';
		} else {
			document.getElementById("usertab").style.display = 'none';
			document.getElementById("tabIcon").innerHTML = '+';
		}
	}

	// Operations when showing message.
	function showMessage(msgTitle, msgText, msgDetail) {
		var msgTab = document.getElementById('message');
		document.getElementById('messageTitle').innerHTML = msgTitle;
		document.getElementById('messageText').innerHTML = msgText;
		document.getElementById('messageDetail').innerHTML = msgDetail;
		msgTab.style.display = "block";
	}

	// Perform user operations.
	function userButton(auth) {
		var state = document.getElementById('signInButton').innerHTML;
		if (state === "Sign Out") {
			document.getElementById("signInButton").innerHTML = "Sign In";
			auth.signOut();
			showSignedOut();
		} else {
			auth.getSession();
		}
	}

	// Operations when signed in.
	function showSignedIn(session) {
		document.getElementById("statusNotAuth").style.display = 'none';
		document.getElementById("statusAuth").style.display = 'block';
		document.getElementById("signInButton").innerHTML = "Sign Out";
		if (session) {
			var idToken = session.getIdToken().getJwtToken();
			if (idToken) {
				var payload = idToken.split('.')[1];
				var tokenobj = JSON.parse(atob(payload));
				var formatted = JSON.stringify(tokenobj, undefined, 2);
				document.getElementById('idtoken').innerHTML = "\nid token:\n" + formatted;
			}
			var accToken = session.getAccessToken().getJwtToken();
			if (accToken) {
				var payload = accToken.split('.')[1];
				var tokenobj = JSON.parse(atob(payload));
				var formatted = JSON.stringify(tokenobj, undefined, 2);
				document.getElementById('acctoken').innerHTML = "\nacc token:\n" + formatted;
			}
			var refToken = session.getRefreshToken().getToken();
			if (refToken) {
				document.getElementById('reftoken').innerHTML = "\nref token:\n" + refToken.substring(1, 20);
			}
		}
		openTab("userdetails");
	}

	// Operations when signed out.
	function showSignedOut() {
		document.getElementById("statusNotAuth").style.display = 'block';
		document.getElementById("statusAuth").style.display = 'none';
		document.getElementById('idtoken').innerHTML = "id token ... ";
		document.getElementById('acctoken').innerHTML = "acc token ... ";
		document.getElementById('reftoken').innerHTML = "ref token ... ";
		closeTab("userdetails");
	}

	// Initialize a cognito auth object.
	function initCognitoSDK() {
		var authData = {
			ClientId : '2a630eoreacbi4c3tqbjjdtjkk', // Your client id here
			AppWebDomain : 'tagphoto.auth.ap-northeast-1.amazoncognito.com', // Exclude the "https://" part. 
			TokenScopesArray : ['openid', 'email', 'profile', 'aws.cognito.signin.user.admin'], // like ['openid','email','phone']...
			RedirectUriSignIn : 'http://localhost/tagphoto/dummy.php',
			RedirectUriSignOut : 'http://localhost/tagphoto/dummy.php',
			// IdentityProvider : 'Facebook', 
					UserPoolId : userPoolId, 
					AdvancedSecurityDataCollectionFlag : 0
		};
		var auth = new AmazonCognitoIdentity.CognitoAuth(authData);
		// You can also set state parameter
		// 後端隨機產生 state
		auth.setState('<?php echo $_SESSION['state'];?>');
		auth.userhandler = {
			onSuccess: function(result) {
				AWSCognito.config.update({region: 'ap-northeast-1'});

				// 前端取得登入使用者的 credential 法
				AWSCognito.config.credentials = new AWSCognito.CognitoIdentityCredentials({
					IdentityPoolId: 'ap-northeast-1:6e9f34bf-bd94-4f4f-8c63-12ab542ac14c',
					Logins: {
						'cognito-idp.ap-northeast-1.amazonaws.com/ap-northeast-1_JACElFd4C': result.getIdToken().getJwtToken()
					}
				});

				AWSCognito.config.credentials.get(function(err){
					if (err) return console.log("Error", err);
					// 成功透過 identity provider 登入 AWS Cognito，取得 identity id，不知道有沒有其他取得 identity id 的方法？
					var identity_id = AWSCognito.config.credentials.identityId;
					console.log("Cognito Identity Id", AWSCognito.config.credentials.identityId);
					//* 吐給後端，必要時讓後端也可以得到授權使用服務 (根據忘了在哪看到官方帳號的回應是不給後端直接登入, SDK 沒對應的 function，所以只能前端登入丟 id 與 token 供後端認證，但建議還是全由前端做)
					jQuery.get("login.php?" + window.location.hash.substr(1).split('&').filter((q)=>{return q.match(/^state=/)})[0] + "&identity_id=" + identity_id + "&id_token=" + result.getIdToken().getJwtToken()).done(function(data){
						console.log(data);
					});
					//*/
				});

				console.log("Sign in success");
				showSignedIn(result);

			},
			onFailure: function(err) {
				// put some error message test here

				let allowedProviders = ['Facebook', 'Google', 'OrdID'];
				console.log ("Error!" + err);

				let err_ = decodeURIComponent(err).replace(/\+/g, " ");
				let providerRegexp = new RegExp(" ((" + allowedProviders.join("|") + ")_\\d+) ");

				let provider_user_id = providerRegexp.exec(err_);
				if (!!provider_user_id) {
					let provider = providerRegexp.exec(err_)[2];
					authData.IdentityProvider = provider;
					let reAuth = new AmazonCognitoIdentity.CognitoAuth(authData);
					userButton(reAuth);
				}
				else {
					console.log("Signin failed due to Unknown reason.");
				}
			}
		};
		// The default response_type is "token", uncomment the next line will make it be "code".
		// auth.useCodeGrantFlow();
		return auth;
	}
	</script>
</body>
</html>


