//
//  VisaService.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 12/5/17.
//  Copyright © 2017 Beyond Management. All rights reserved.
//

import Foundation

class Visa{
    
    var id = 0
    var name = ""
    var imgUrl = ""
    var info = ""
    var basicPrice = 0
    var basicInfo = ""
    var interPrice = 0
    var interInfo = ""
    var advPrice = 0
    var advInfo = ""
 
    init(id: Int, name: String, imgUrl: String, info:String, basicPrice: Int, basicInfo:String, interPrice: Int, interInfo: String,
         advPrice:Int, advInfo: String) {
        
        self.id = id
        self.name = name
        self.imgUrl = imgUrl
        self.info = info
        self.basicPrice = basicPrice
        self.basicInfo = basicInfo
        self.interPrice = interPrice
        self.interInfo = interInfo
        self.advInfo = advInfo
        self.advPrice = advPrice
        
    }
}
