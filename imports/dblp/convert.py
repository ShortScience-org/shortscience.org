import xml.etree.ElementTree as ET
tree = ET.parse('nips2017.xml')
root = tree.getroot()

def toString(it, join_str=" "):
    return join_str.join([e.text for e in it])

data = []
for paper in root.iter('inproceedings'):
    
    bibtexKey = paper.attrib["key"]
    title = toString(paper.iter('title'))
    authors = toString(paper.iter('author')," and ")
    venue = toString(paper.iter('booktitle'))
    urls = toString(paper.iter('ee'))
    year = toString(paper.iter('year'))
    
    data.append({"bibtexKey": bibtexKey,
                 "title": title,
                 "authors": authors,
                 "venue": venue,
                 "year": year,
                 "urls": urls,
                 "source": "dblp"})

    
import pandas as pd

df = pd.DataFrame(data)

df.to_csv("nips2017.csv", sep=',' , encoding='utf-8')
