//
//  Document.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 1/6/18.
//  Copyright © 2018 Beyond Management. All rights reserved.
//

import Foundation

class Requirement {
    
    var id = 0
    var name = ""
    var info = ""
    var img = ""
    var status = ""
    
    init(id: Int, name: String, info: String, img: String, status: String){
        self.id = id
        self.name = name
        self.info = info
        self.img = img
        self.status = status
    }
    
    
    init(){
        
    }
}
