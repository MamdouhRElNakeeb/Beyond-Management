//
//  ContactVC.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 12/20/17.
//  Copyright © 2017 Beyond Management. All rights reserved.
//

import UIKit

class ContactVC: UIViewController {

    var skypeIV = UIImageView()
    var skypeLbl = UILabel()
    var skypeBtn = UIButton()
    
    var lineV = UIView()
    
    var sendMsgLbl = UILabel()
    var msgTV = UITextView()
    var sendBtn = UIButton()
    
    override func viewDidLoad() {
        super.viewDidLoad()

        // Do any additional setup after loading the view.
        
        initSkype()
        initMsgView()
    }

    
    func initSkype (){
        
        skypeIV.frame = CGRect(x: 16, y: 16, width: 60, height: 60)
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
        
    }
}
