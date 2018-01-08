//
//  Urls.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 12/6/17.
//  Copyright © 2017 Beyond Management. All rights reserved.
//

import Foundation

class Urls {
    
    static var BASE_URL = "http://bm.nakeeb.me/"
    static var REGISTER_USER = BASE_URL + "register.php"
    static var LOGIN_USER = BASE_URL + "login.php"
    static var VISA_SERVICES = BASE_URL + "getVisa.php"
    static var VISA_IMGS = BASE_URL + "ServicesImgs/"
    static var USER_APPS = BASE_URL + "getApps.php"
    static var APP_REQ = BASE_URL + "getAppReq.php"
    static var DOC_IMGS = BASE_URL + "DocsImgs/"
    static var REQ_SUBMIT = BASE_URL + "submitReq.php"
    static var CONTACT_MSG = BASE_URL + "contactMsg.php"
    
    static var PAYMENTS = "http://bm.nakeeb.me/payments/"
    static var PAY_NONCE_VAULT = PAYMENTS + "create_payment_method.php"
    static var PAY_SKYPE = PAYMENTS + "create_payment_method_skype.php"
    static var PAY_AMOUNT = PAYMENTS + "create_charge.php"
    static var CLIENT_TOKEN = PAYMENTS + "create_token.php"
}
