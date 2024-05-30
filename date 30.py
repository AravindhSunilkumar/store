#reverse p 2 check palindrome
'''
s=input('enter a string')
rev=''
i=0
while i<len(s):
    rev=s[i]+rev
    i+=1
print("reverse=",rev)

if s == rev:
    print(rev,'is a palindrome')
else:
    print(rev,'is not a palindrome')
'''
#sum of numbers in in a list
'''
l=[1,2,3,4,5,6,7,8]
s=0
i=0
while i<8:
    s+=l[i]
    i+=1
print('Sum=',s)
'''
#find strings in a list
'''
l=['apple',23,24,'orange',3.5]
i=0
while i<len(l):
    if type(l[i]) == str:
        print(l[i])
    i+=1
'''
#revers a list
'''
l=[1,2,3,4,5,6,7,8]
rev=[]
i=len(l)-1
while i>=0:
    print(l[i])
    i-=1
'''
#1
'''
s=input('Enter a string :')
i=1
s2=rev=''
while i<len(s):
    if i%2==0:
        rev=s[i]+rev
        s2+=s[i]
    i+=1
if rev == s2:
    print(rev,'is palindrome')
else:
    print(rev,'is not palindrome ')
'''
#2
'''
l=['apple','orange',1,2,3.5,3,4,'banana',5,6]
i=0
print('Even numbers ')
while i<10:
    if type(l[i]) == int :
        if l[i]%2==0:
            print(l[i])
    i+=1
'''
#3 prime number
l=['apple','orange',1,2,3.5,3,4,'banana',5,6]
i=0
print('prime numbers are ')
while i<10:
    if type(l[i]) == int:
        j=2
        while j<l[i]:
            if l[i]%2 !=0:
                print(l[i])
            j+=1
    i+=1
    
