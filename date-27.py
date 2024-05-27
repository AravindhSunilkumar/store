'''
starting=int(input("Enter your starting number"))
ending=int(input("Enter your ending number"))
'''
'''
while starting<=ending:
    print(starting)
    starting+=1
'''

#print odd number
'''
while starting<=ending:
    if starting%2!=0:
        print(starting)
    starting+=1
'''
#reverse
'''
while starting<=ending:
    print(ending)
    ending-=1
'''
c = input('Enter your string : ')
count=0
i=0
while i<len(c):
    if c[i] == 'a' or c[i] == 'e' or c[i] == 'i' or c[i] == 'o' or c[i] == 'u' or c[i] == 'A' or c[i] == 'E' or c[i] == 'I' or c[i] == 'O' or c[i] == 'U' :
        count+=1
    i+=1
print('vowels =',count)
