//
//  ContactVC.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 12/20/17.
//  Copyright © 2017 Beyond Management. All rights reserved.
//

import UIKit
import Braintree
import BraintreeDropIn
import Alamofire

class ContactVC: UIViewController, BTAppSwitchDelegate, BTViewControllerPresentingDelegate  {

    
    
    @IBOutlet weak var skypeBtn: UIButton!
    @IBOutlet weak var msgTV: UITextView!
    @IBOutlet weak var sendBtn: UIButton!
    
    var amount = 50
    
    var skypeIV = UIImageView()
    var skypeLbl = UILabel()
//    var skypeBtn = UIButton()
    
    var lineV = UIView()
    
    var sendMsgLbl = UILabel()
//    var msgTV = UITextView()
//    var sendBtn = UIButton()
    
    let indicator = UIActivityIndicatorView(activityIndicatorStyle: UIActivityIndicatorViewStyle.white)
    
    var braintreeClient: BTAPIClient!
    var clientToken = ""
    
    override func viewDidLoad() {
        super.viewDidLoad()

        // Do any additional setup after loading the view.
        let barBtnItem = UIBarButtonItem(customView: indicator)
        self.navigationItem.rightBarButtonItem = barBtnItem
        
//        initSkype()
//        initMsgView()
        
        fetchClientToken()
    }

    // Payment
    func showDropIn(clientTokenOrTokenizationKey: String, amount: Int) {
        
        indicator.startAnimating()
        
        let request =  BTDropInRequest()
        request.amount = "\(amount)"
        
        if clientTokenOrTokenizationKey.isEmpty {
            indicator.stopAnimating()
            return
        }
        
        let dropIn = BTDropInController(authorization: clientTokenOrTokenizationKey, request: request){
            (controller, result, error) in
            
            self.indicator.stopAnimating()
            
            if (error != nil) {
                print("ERROR")
            } else if (result?.isCancelled == true) {
                print("CANCELLED")
            } else if let result = result {
                // Use the BTDropInResult properties to update your UI
                // result.paymentOptionType
                // result.paymentMethod
                // result.paymentIcon
                // result.paymentDescription
                
                print(result.paymentMethod?.nonce ?? "")
                
                self.postNonceToServerVault((result.paymentMethod?.nonce ?? ""), amount)
            }
            controller.dismiss(animated: true, completion: nil)
        }
        
        self.present(dropIn!, animated: true, completion: nil)
    }
    
    func postNonceToServerVault(_ paymentMethodNonce: String, _ amount: Int) {
        
        indicator.startAnimating()
        //let dataCollector = BTDataCollector(environment: .Sandbox)
        //let deviceData = dataCollector.collectCardFraudData()
        
        let deviceData = PPDataCollector.collectPayPalDeviceData()
        let customerId = UserDefaults().string(forKey: "customerId")!
        print("postNonceToServerVault deviceData: \(deviceData)")
        
        let params: Parameters = [
            "payment_method_nonce": paymentMethodNonce,
            "customerId": customerId,
            "amount": "\(amount)"
        ]
        
        print(params)
        
        Alamofire.request(Urls.PAY_SKYPE, method: .post, parameters: params).responseJSON{
            
            response in
            
            print(response)
            
            if let result = response.result.value {
                
                let json = result as! NSDictionary
                
                if let success = json.value(forKey: "paySuccess") as? Bool{
                    
                    if success{
                        let alert = UIAlertController(title: "Success", message: "Thank you, Your request have been submitted.\n You will be contacted for more details.", preferredStyle: UIAlertControllerStyle.alert)
                        alert.addAction(UIAlertAction(title: "OK", style: UIAlertActionStyle.default, handler: nil))
                        self.present(alert, animated: true, completion: nil)
                    }
                    else {
                        let alert = UIAlertController(title: "Error", message: json.value(forKey: "payError") as? String, preferredStyle: UIAlertControllerStyle.alert)
                        alert.addAction(UIAlertAction(title: "Try Again", style: UIAlertActionStyle.destructive, handler: nil))
                        self.present(alert, animated: true, completion: nil)
                    }
                    
                }
                else if let success = json.value(forKey: "methodSuccess") as? Bool{
                    
                    if success{
                        let alert = UIAlertController(title: "Success", message: "Your payment method have been added.", preferredStyle: UIAlertControllerStyle.alert)
                        alert.addAction(UIAlertAction(title: "OK", style: UIAlertActionStyle.default, handler: nil))
                        self.present(alert, animated: true, completion: nil)
                    }
                    else {
                        let alert = UIAlertController(title: "Error", message: json.value(forKey: "methodError") as? String, preferredStyle: UIAlertControllerStyle.alert)
                        alert.addAction(UIAlertAction(title: "Try Again", style: UIAlertActionStyle.destructive, handler: nil))
                        self.present(alert, animated: true, completion: nil)
                    }
                    
                }
                
                
            }
            
            self.indicator.stopAnimating()
        }
        
    }
    
    
    func fetchClientToken(){
        
        let parameters: Parameters = [
            "customerId": UserDefaults().string(forKey: "customerId")!
        ]
        
        print(parameters)
        
        Alamofire.request(Urls.CLIENT_TOKEN, method: .post, parameters: parameters)
            .responseJSON{
                
                response in
                
                print(response)
                
                if let result = response.result.value {
                    
                    let json = result as! NSDictionary
                    self.clientToken = json.value(forKey: "token") as! String
                    
                }
        }
        
    }
    
    // MARK: - BTViewControllerPresentingDelegate
    func paymentDriver(_ driver: Any, requestsDismissalOf viewController: UIViewController) {
        dismiss(animated: true, completion: nil)
    }
    
    func paymentDriver(_ driver: Any, requestsPresentationOf viewController: UIViewController) {
        present(viewController, animated: true, completion: nil)
    }
    
    // MARK: - BTAppSwitchDelegate
    func appSwitcherWillPerformAppSwitch(_ appSwitcher: Any) {
        
    }
    
    func appSwitcher(_ appSwitcher: Any, didPerformSwitchTo target: BTAppSwitchTarget) {
        
    }
    
    func appSwitcherWillProcessPaymentInfo(_ appSwitcher: Any) {
        
    }
    
    @IBAction func skypeBtnOnClick(_ sender: UIButton) {
        if clientToken.isEmpty{
            fetchClientToken()
        }
        showDropIn(clientTokenOrTokenizationKey: clientToken, amount: amount)
    }
    
    @IBAction func sendBtnOnClick(_ sender: UIButton) {
        
        if (msgTV.text.isEmpty){
            let alert = UIAlertController(title: "Error", message: "Please write a message", preferredStyle: UIAlertControllerStyle.alert)
            alert.addAction(UIAlertAction(title: "Try Again", style: UIAlertActionStyle.destructive, handler: nil))
            self.present(alert, animated: true, completion: nil)
            return
        }
        
        let params: Parameters = [
            "user_id": UserDefaults().string(forKey: "id")!,
            "msg": msgTV.text!
        ]
     
        print(params)
        
        Alamofire.request(Urls.CONTACT_MSG, method: .post, parameters: params).responseJSON{
            
            response in
            
            print(response)
            if let result = response.result.value{
                
                if let json = result as? NSDictionary{
                    
                    if let success = json.value(forKey: "success") as? Bool{
                        
                        if success{
                            let alert = UIAlertController(title: "Success", message: "Thank you, Your message have been sent.\n You will be contacted fore more details.", preferredStyle: UIAlertControllerStyle.alert)
                            alert.addAction(UIAlertAction(title: "OK", style: UIAlertActionStyle.default, handler: nil))
                            self.present(alert, animated: true, completion: nil)
                        }
                        else {
                            let alert = UIAlertController(title: "Error", message: json.value(forKey: "message") as? String, preferredStyle: UIAlertControllerStyle.alert)
                            alert.addAction(UIAlertAction(title: "Try Again", style: UIAlertActionStyle.destructive, handler: nil))
                            self.present(alert, animated: true, completion: nil)
                        }
                        
                    }
                    
                }
            }
            
        }
    }
    
    @objc func skypeRequest(_ sender: UIButton){
        
        if clientToken.isEmpty{
            fetchClientToken()
        }
        showDropIn(clientTokenOrTokenizationKey: clientToken, amount: Int((skypeBtn.titleLabel?.text)!)!)
    }
    
    func initSkype (){
        
        skypeIV.frame = CGRect(x: 16, y: self.view.frame.minY + 16, width: 60, height: 60)
        skypeIV.image = UIImage(named: "skype_icn")
        skypeIV.contentMode = .scaleAspectFit
        
        skypeBtn.frame = CGRect(x: view.frame.width - 86, y: skypeIV.frame.minY + 10, width: 70, height: 40)
        skypeBtn.setTitle("$50", for: .normal)
        skypeBtn.setTitleColor(UIColor.white, for: .normal)
        skypeBtn.backgroundColor = UIColor.primaryColor()
        skypeBtn.layer.cornerRadius = 20
        skypeBtn.layer.masksToBounds = true
        
        skypeLbl.frame = CGRect(x: skypeIV.frame.maxX + 10, y: skypeIV.frame.minY, width: skypeBtn.frame.minX - skypeIV.frame.maxX - 20, height: 60)
        skypeLbl.text = "Request a Skype call"
        
        self.view.addSubview(skypeIV)
        self.view.addSubview(skypeBtn)
        self.view.addSubview(skypeLbl)
        
        skypeBtn.addTarget(self, action: #selector(self.skypeRequest(_:)), for: .touchUpInside)
        
    }

    func initMsgView (){
    
        lineV.frame = CGRect(x: skypeIV.frame.minX, y: skypeIV.frame.maxY + 16, width: view.frame.width - 32, height: 1)
        lineV.backgroundColor = UIColor.gray
        
        sendMsgLbl.frame = CGRect(x: lineV.frame.minX, y: lineV.frame.maxY + 16, width: view.frame.width - 32, height: 21)
        sendMsgLbl.text = "Send message"
        
        msgTV.frame = CGRect(x: sendMsgLbl.frame.minX, y: sendMsgLbl.frame.maxY + 8, width: view.frame.width - 32, height: 200)
        msgTV.backgroundColor = UIColor.greyLightColor()
        msgTV.text = "Your message here"
        
        sendBtn.frame = CGRect(x: sendMsgLbl.frame.minX, y: msgTV.frame.maxY + 10, width: view.frame.width - 32, height: 40)
        sendBtn.setTitleColor(UIColor.white, for: .normal)
        sendBtn.backgroundColor = UIColor.primaryColor()
        sendBtn.setTitle("Send", for: .normal)
        
        self.view.addSubview(lineV)
        self.view.addSubview(sendMsgLbl)
        self.view.addSubview(sendBtn)
        
        
    }
}
